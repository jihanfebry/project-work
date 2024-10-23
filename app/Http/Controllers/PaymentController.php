<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function uploadReceipt(Request $request)
    { 
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak terautentikasi'], 401);
        }

        $request->validate([
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('receipt_image')) {
            $imagePath = $request->file('receipt_image')->store('receipt_images', 'public');
            
            $paymentReceipt = Payment::create([
                'user_id' => $user->id, 
                'receipt_image' => $imagePath,
                'status' => 'menunggu konfirmasi', // Pastikan kolom status ada di model
            ]);

            return response()->json([
                'message' => 'Bukti pembayaran berhasil diunggah',
                'image_path' => $imagePath,
                'payment_receipt' => $paymentReceipt 
            ], 201);
        }
        return response()->json(['error' => 'Tidak ada file yang diunggah'], 400);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
