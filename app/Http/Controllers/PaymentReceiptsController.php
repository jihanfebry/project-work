<?php

namespace App\Http\Controllers;

use App\Models\paymentReceipts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentReceiptsController extends Controller
{
    public function updateStatus(Request $request, $userId)
    {
    $user = $request->user();
    if (!$user) {
        return response()->json(['error' => 'Pengguna tidak terautentikasi'], 401);
    }

    $request->validate([
        'status' => 'required|in:belum lunas,lunas',
    ]);

    $updatedRows = PaymentReceipts::where('user_id', $userId)
                                   ->update(['status' => $request->status]);
    if ($updatedRows === 0) {
        return response()->json(['error' => 'Tidak ada catatan pembayaran yang ditemukan untuk user ini'], 404);
    }

    return response()->json(['message' => 'Status pembayaran berhasil diperbarui']);
    }

    public function index()
    {
        $receipts = paymentReceipts::with('user')->get();
        return response()->json($receipts);
    }

    public function show($id)
    {
        $paymentReceipt = paymentReceipts::with('user')->findOrFail($id);
        return response()->json($paymentReceipt);
    }

    public function destroy($id)
    {
        $paymentReceipt = paymentReceipts::findOrFail($id);
        Storage::disk('public')->delete($paymentReceipt->receipt_image);
        $paymentReceipt->delete();

        return response()->json(null, 204);
    }

    public function notifyUsers(Request $request)
{
    // Ambil semua pengguna
    $users = User::all();
    $notifications = [];
    $currentMonth = Carbon::now()->translatedFormat('F Y'); // Mengambil bulan dan tahun sekarang

    foreach ($users as $user) {
        // Cek apakah sudah ada entri pembayaran untuk bulan ini
        $payment = PaymentReceipts::firstOrCreate(
            [
                'user_id' => $user->id,
                'month' => $currentMonth, // Pastikan kolom 'month' diisi
            ],
            [
                'status' => 'belum dibayar',
                'receipt_image' => '', // Set nilai default jika tidak ada gambar
            ]
        );

        // Simpan notifikasi ke dalam array
        $notifications[] = [
            'user_id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'payment_id' => $payment->id,
            'status' => $payment->status,
            'notification' => 'Notifikasi pembayaran untuk bulan ' . $currentMonth . ' telah dikirim.'
        ];
    }

    return response()->json([
        'message' => 'Notifikasi pembayaran dikirim ke semua pengguna.',
        'notifications' => $notifications,
    ], 200);
}
}