<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('gurus')->get();

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
        $created = Carbon::now();

        $request->validate([
            'name' => 'required|string|max:255',
            'no_handphone' => 'required|string|max:15',
            'email' => 'required|email|unique:gurus,email'
        ]);
        
        $data = DB::table('gurus')->insert([
            'name' => $request->name,
            'no_handphone' => $request->no_handphone,
            'email' => $request->email,
            'created_at' => $created
        ]);
        
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]); 
        } else {
            return response()->json([
                'success' => false
            ]);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $updated = Carbon::now();

        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validateData = $request->validate([
            'name' => 'string',
            'no_handphone' => 'nullable|string|max:15',
            'email' => 'email|unique:gurus,email,'
        ]);

        DB::beginTransaction();

        try {
            $guru->fill([
                'name' => $request->name,
                'no_handphone' => $request->no_handphone,
                'email' => $request->email,
                'updated_at' => $updated
            ]);

            if ($guru->isDirty()) {
                $guru->save();
            }

            if (($request->filled('name') || $request->filled('email')) && $guru->user_id) {
                $user = $guru->user; // Pastikan ada relasi user di model Siswa

                $dataToUpdate = [];
                if ($request->filled('name')) {
                    $dataToUpdate['name'] = $request->name;
                }
                if ($request->filled('email')) {
                    $dataToUpdate['email'] = $request->email;
                }

                $user->update($dataToUpdate);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $guru->fresh() // Ambil data terbaru
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json(['message' => 'Guru not found'], 404);
        }
        $guru->delete();
        return response()->json(['message' => 'Guru deleted successfully']);
    }
}
