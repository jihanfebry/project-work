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

        $user = Guru::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'no_handphone' => 'required|string|max:15',
            'email' => 'required|email|unique:gurus,email,' . $id,
        ]);

        $updateSuccess = $user->update([
            'no_handphone' => $request->no_handphone,
            'email' => $request->email,
            'updated_at' => $updated
        ]);

        if ($updateSuccess) {
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Update data failed'
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Guru::find($id);

        if (!$user) {
            return response()->json(['message' => 'Guru not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'Guru deleted successfully']);
    }
}
