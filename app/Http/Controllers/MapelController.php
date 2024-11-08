<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
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
        $data = DB::table('mapels')->insert([
            'mapel' => $request->mapel
        ]);

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add data'
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     */
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
    public function update(Request $request, $id)
    {
        // Validasi input request
        $request->validate([
            'mapel' => 'required|string|max:255',
        ]);

        // Cari mapel berdasarkan ID
        $mapel = Mapel::find($id);

        if (!$mapel) {
            return response()->json([
                'success' => false,
                'message' => 'Mapel not found'
            ], 404);
        }

        // Update mapel
        $mapel->mapel = $request->mapel;
        $mapel->save();

        return response()->json([
            'success' => true,
            'data' => $mapel
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cari mapel berdasarkan ID
        $mapel = Mapel::find($id);

        if (!$mapel) {
            return response()->json([
                'success' => false,
                'message' => 'Mapel not found'
            ], 404);
        }

        // Hapus mapel
        $mapel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mapel deleted successfully'
        ]);
    }
}
