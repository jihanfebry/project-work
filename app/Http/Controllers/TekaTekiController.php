<?php

namespace App\Http\Controllers;

use App\Models\TekaTeki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class TekaTekiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     // Mendapatkan teka-teki acak
     public function index()
     {
         $tekaTeki = TekaTeki::inRandomOrder()->first();
         return response()->json($tekaTeki);
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
 


    // Method untuk menambahkan teka-teki baru
    public function store(Request $request)
    {
        $request->validate([
            'gambar' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi file gambar
            'jawaban' => 'required|string',
        ]);

        // Menyimpan gambar ke storage
        $path = $request->file('gambar')->store('public/gambar_teka_teki');
        $url = Storage::url($path);

        $jawaban = $request->input('jawaban');
        $clue = TekaTeki::generateClue($jawaban);

        $tekaTeki = TekaTeki::create([
            'gambar' => $url, // Simpan URL gambar ke dalam database
            'jawaban' => $jawaban,
            'clue' => $clue,
        ]);

        return response()->json(['message' => 'Teka-teki berhasil ditambahkan', 'data' => $tekaTeki], 201);
    }



   

    // Memeriksa jawaban yang diberikan oleh pengguna
    public function cekJawaban(Request $request)
    {
        $tekaTeki = TekaTeki::find($request->input('id'));

        if (!$tekaTeki) {
            return response()->json(['error' => 'Teka-teki tidak ditemukan'], 404);
        }

        $jawaban = strtolower($request->input('jawaban'));
        if ($jawaban === strtolower($tekaTeki->jawaban)) {
            return response()->json(['message' => 'Jawaban benar!']);
        } else {
            return response()->json(['message' => 'Jawaban salah!']);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(TekaTeki $tekaTeki)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TekaTeki $tekaTeki)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TekaTeki $tekaTeki)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TekaTeki $tekaTeki)
    {
        //
    }
}
