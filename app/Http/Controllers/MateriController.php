<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriController extends Controller
{
    /**
     * Display a l  isting of the resource.
     */
    public function index()
    {
        $data = DB::table('materis')->get();

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
        // Validasi input
        $request->validate([
            'subject' => 'required|string|max:255',
            'material' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

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

        // Simpan data materi ke database
        $data = DB::table('materis')->insert([
            'image' => $imagePath, // Simpan path gambar atau null
            'subject' => $request->subject,
            'material' => $request->material,
        ]);

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Materi berhasil ditambahkan',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan materi'
            ], 403);
        }
    }


    public function show(Materi $materi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materi $materi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materi $materi)
    {
        if (!$materi) {
            return response()->json(['message' => 'Materi tidak ditemukan'], 404);
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
            $imagePath = $materi->image;
        }
    
        // Update data di database
        $updateSuccess = $materi->update([
            'image' => $imagePath, // Simpan path gambar yang baru atau tetap
            'material' => $request->input('material'),
            'subject' => $request->input('subject'),
        ]);
    
        if ($updateSuccess) {
            return response()->json([
                'success' => true,
                'data' => $materi,
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
    public function destroy(Materi $materi)
    {
        //
    }
}