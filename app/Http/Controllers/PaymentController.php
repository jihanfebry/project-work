<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentReceipts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{

    public function index()
    {
        // Ambil pengguna yang bukan admin atau guru
        $users = User::with('payments')
            ->whereNotIn('role', ['admin', 'guru'])
            ->get();
    
        $result = $users->map(function ($user) {
            // Pembayaran terakhir
            $lastPayment = $user->payments->last();
            
            return [
                'user_id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'status' => $lastPayment->status ?? 'belum dibayar',
                'spp_bulan' => $lastPayment ? \Carbon\Carbon::parse($lastPayment->month)->translatedFormat('F') : 'Belum ada pembayaran',
            ];
        });
    
        return response()->json([
            'message' => 'Daftar pengguna dengan status pembayaran',
            'data' => $result,
        ], 200);
    }
    

    public function getUserWithImage()
    {
        // Ambil semua pengguna dengan pembayaran yang memiliki status 'menunggu konfirmasi' atau 'lunas'
        $users = User::with(['payments' => function ($query) {
            $query->whereIn('status', ['menunggu konfirmasi', 'lunas'])
                  ->whereNotNull('receipt_image'); // Pastikan bukti gambar telah diunggah
        }])->whereHas('payments', function ($query) {
            $query->whereIn('status', ['menunggu konfirmasi', 'lunas'])
                  ->whereNotNull('receipt_image'); // Filter berdasarkan status dan gambar
        })->get();
    
        // Format hasil
        $result = $users->map(function ($user) {
            // Pembayaran terakhir sesuai filter
            $lastPayment = $user->payments->last();
    
            return [
                'user_id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'status' => $lastPayment->status ?? 'belum ada status',
                'spp_bulan' => $lastPayment ? \Carbon\Carbon::parse($lastPayment->month)->translatedFormat('F') : 'Belum ada pembayaran',
                'proof_image' => $lastPayment->receipt_image ? $lastPayment->receipt_image : null, // Hanya path relatif
            ];
        });
    
        return response()->json([
            'message' => 'Daftar pengguna dengan pembayaran menunggu konfirmasi atau lunas',
            'data' => $result,
        ], 200);
    }
    
    

    // Fungsi untuk mengirim notifikasi ke semua siswa
    public function notifyUsers()
    {
        $students = User::where('role', 'siswa')->get();
        $currentMonth = Carbon::now()->translatedFormat('F Y');
        $notifications = [];

        foreach ($students as $student) {
            // Buat atau ambil data pembayaran
            $payment = Payment::firstOrCreate(
                ['user_id' => $student->id, 'month' => $currentMonth],
                ['status' => 'belum dibayar']
            );

            $notifications[] = [
                'user_id' => $student->id,
                'name' => $student->name,
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'notification' => "Notifikasi pembayaran untuk bulan $currentMonth telah dikirim.",
            ];
        }

        return response()->json([
            'message' => 'Notifikasi dikirim ke semua siswa.',
            'notifications' => $notifications,
        ], 200);
    }


    // Fungsi untuk upload bukti pembayaran
    public function uploadReceipt(Request $request)
    {
        $user = Auth::user();
    
        if (!$user || $user->role !== 'siswa') {
            return response()->json(['error' => 'Hanya siswa yang dapat mengunggah bukti pembayaran.'], 403);
        }
    
        $request->validate([
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('receipt_image')) {
            // Simpan gambar di storage/public/receipt_images
            $imagePath = $request->file('receipt_image')->store('receipt_images', 'public');
    
            // Ambil pembayaran untuk user yang sedang login
            $payment = Payment::where('user_id', $user->id)
                              ->where('month', Carbon::now()->translatedFormat('F Y'))
                              ->first();
    
            if (!$payment) {
                return response()->json(['error' => 'Pembayaran tidak ditemukan.'], 404);
            }
    
            // Update pembayaran dengan gambar dan status
            $payment->update([
                'receipt_image' => $imagePath, // Simpan path relatif
                'status' => 'menunggu konfirmasi',
            ]);
    
            return response()->json([
                'message' => 'Bukti pembayaran berhasil diunggah.',
                'payment' => $payment,
            ], 201);
        }
    
        return response()->json(['error' => 'Tidak ada file yang diunggah.'], 400);
    }
    

public function show($id)
{
    // Cari pengguna berdasarkan ID dan relasi payments
    $user = User::with('payments')
        ->whereNotIn('role', ['admin', 'guru']) // Tidak termasuk admin atau guru
        ->find($id);

    // Jika pengguna tidak ditemukan
    if (!$user) {
        return response()->json([
            'message' => 'User not found or does not have payment records',
        ], 404);
    }

    // Pembayaran terakhir
    $lastPayment = $user->payments->last();

    // Menentukan bulan pembayaran
    $sppBulan = $lastPayment ? \Carbon\Carbon::parse($lastPayment->month)->translatedFormat('F') : 'Belum ada pembayaran';

    return response()->json([
        'message' => 'Detail pengguna dengan status pembayaran',
        'data' => [
            'user_id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'status' => $lastPayment->status ?? 'belum dibayar',
            'spp_bulan' => $sppBulan
        ],
    ], 200);
}


    // Fungsi untuk admin memvalidasi bukti pembayaran
  // Fungsi untuk admin memvalidasi bukti pembayaran
  public function validatePayment(Request $request, $userId)
  {
      $request->validate([
          'status' => 'required|in:lunas,belum dibayar',
      ]);
  
      // Temukan pembayaran berdasarkan user_id
      $payment = Payment::where('user_id', $userId)->firstOrFail();
  
      // Update status pembayaran
      $payment->update([
          'status' => $request->status,
      ]);
  
      return response()->json([
          'message' => 'Status pembayaran berhasil diperbarui.',
          'payment' => $payment
        ]);
  }


}