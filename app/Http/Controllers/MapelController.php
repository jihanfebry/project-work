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
        // Cek apakah file gambar ada (tidak required)
        if ($request->hasFile('image')) {
            // Ambil file gambar
            $image = $request->file('image');
            // Menggunakan nama asli file gambar
            $imageName = $image->getClientOriginalName();
            // Simpan gambar ke folder public/image
            $image->move(public_path('image'), $imageName);
            // Buat path yang akan disimpan di database
            $imagePath = 'image/'.$imageName;
        } else {
            // Jika tidak ada gambar, set path kosong atau sesuai kebutuhan
            $imagePath = null;
        }

        // Simpan data ke database
        $data = DB::table('mapels')->insert([
            'image' => $imagePath, // Simpan path gambar atau null
            'material' => $request->material,
        ]);

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data update failed'
            ], 403);
        }
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

        $request->validate([
            'material' => 'required|min:5'
        ]);

        $updateSuccess = $mapel->update([
            'material' => $request->input('material')
        ]);

        if ($updateSuccess) {
            return response()->json([
                'success' => true,
                'data' => $mapel
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Update gagal'
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
