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
    $users = User::with('payments')->get();

    $result = $users->map(function ($user) {
        return [
            'user_id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'status' => $user->payments->last()->status ?? 'belum dibayar', // Ambil status terakhir jika ada
        ];
    });

    return response()->json([
        'message' => 'Daftar pengguna dengan status pembayaran',
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
            $payment = Payment::firstOrCreate(
                [
                    'user_id' => $student->id,
                ],
                [
                    'status' => 'belum dibayar',
                ]
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
            $imagePath = $request->file('receipt_image')->store('receipt_images', 'public');

            $payment = Payment::where('user_id', $user->id)->first();

            if (!$payment) {
                return response()->json(['error' => 'Pembayaran tidak ditemukan.'], 404);
            }

            $payment->update([
                'receipt_image' => $imagePath,
                'status' => 'menunggu konfirmasi',
            ]);

            return response()->json([
                'message' => 'Bukti pembayaran berhasil diunggah.',
                'payment' => $payment,
            ], 201);
        }

        return response()->json(['error' => 'Tidak ada file yang diunggah.'], 400);
    }

    // Fungsi untuk admin memvalidasi bukti pembayaran
    public function validatePayment(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:lunas,belum dibayar',
        ]);

        $payment = Payment::findOrFail($id);

        $payment->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Status pembayaran berhasil diperbarui.',
            'payment' => $payment,
        ]);
    }
}
