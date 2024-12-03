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
            'teka_teki' => 'required|array|min:1',
            'teka_teki.*.gambar' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'teka_teki.*.jawaban' => 'required|string'
        ]);
    
        $response = []; // Array untuk menyimpan respons setiap teka-teki yang berhasil disimpan
    
        // Loop melalui setiap teka-teki dalam array `teka_teki`
        foreach ($request->input('teka_teki') as $index => $tekaTekiData) {
            $gambar = $request->file("teka_teki.$index.gambar");
            $jawaban = $tekaTekiData['jawaban'];
    
            // Simpan gambar ke storage
            $path = $gambar->store('public/gambar_teka_teki');
            $url = Storage::url($path);
    
            // Generate clue dari jawaban
            $clue = TekaTeki::generateClue($jawaban);
    
            // Simpan teka-teki ke database
            $tekaTeki = TekaTeki::create([
                'gambar' => $url,
                'jawaban' => $jawaban,
                'clue' => $clue,
            ]);
    
            // Tambahkan hasil ke dalam array respons
            $response[] = $tekaTeki;
        }
    
        // Kembalikan array respons berisi teka-teki yang berhasil disimpan
        return response()->json(['message' => 'Teka-teki berhasil ditambahkan', 'teka-teki' => $response], 201);
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
