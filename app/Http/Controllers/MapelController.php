<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapelController extends Controller
{
    /**
     * Display a l  isting of the resource.
     */
    public function index()
    {
        $data = DB::table('mapels')->get();

        return response()->json([
            'data' => $data
        ]);
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
        // Validasi data yang dibutuhkan
        $request->validate([
            'subject' => 'required|min:5',
            'material' => 'required|min:5',
            'image' => 'nullable|image|max:2048', // Validasi gambar
        ]);
    
        // Cek apakah file gambar ada
        if ($request->hasFile('image')) {
            // Menggunakan Laravel's store() method untuk menyimpan gambar
            $imagePath = $request->file('image')->store('images', 'public');
        } else {
            $imagePath = null;
        }
    
        // Simpan data ke database menggunakan Model Eloquent
        $mapel = new Mapel();
        $mapel->subject = $request->subject;
        $mapel->material = $request->material;
        $mapel->image = $imagePath;
        $mapel->save();
    
        return response()->json([
            'success' => true,
            'data' => $mapel
        ]);
    }
    


    public function show(Mapel $mapel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mapel $mapel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mapel $mapel)
    {
        if (!$mapel) {
            return response()->json(['message' => 'Mapel tidak ditemukan'], 404);
        }
    
        // Validasi data yang dibutuhkan
        $request->validate([
            'material' => 'required|min:5',
            'subject' => 'required|min:5',
        ]);
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $image->move(public_path('image'), $imageName);
            $imagePath = 'image/' . $imageName;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar yang ada
            $imagePath = $mapel->image;
        }
    
        // Update data di database
        $updateSuccess = $mapel->update([
            'image' => $imagePath, // Simpan path gambar yang baru atau tetap
            'material' => $request->input('material'),
            'subject' => $request->input('subject'),
        ]);
    
        if ($updateSuccess) {
            return response()->json([
                'success' => true,
                'data' => $mapel,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Update gagal',
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(mapel $mapel)
    {
        //
    }
}
