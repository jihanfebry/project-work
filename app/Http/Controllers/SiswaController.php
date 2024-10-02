<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('siswas')->get();

        return response()->json([
            'data' => $data,
            // 'status' => 404
        ]);
    }

    public function listSiswa()
    {
        $data = DB::table('siswas as s')
            ->join('kelas as k', 's.kelas_id', '=', 'k.id')
            ->select('s.*', 'k.kelas')
            ->get();

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

        $data = [
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            // 'class' => $request->class,
            'parent' => $request->parent,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'addres' => $request->addres,
            'kelas_id' => $request->kelas_id,
            'created_at' => $created
        ];

        $inserted = DB::table('siswas')->insert($data);
        if ($inserted) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'fail' => false
                ], 400); // Gunakan 400 untuk kesalahan validasi
            }

        // if ($inserted) {
        //     $siswa = DB::table('siswas')->where('name', $request->name)->first();
        //     return response()->json([
        //         'success' => true,
        //         'data' => $siswa
        //     ]);
        // } else {
        //     return response()->json([
        //         'success' => false
        //     ], 400); // Gunakan 400 untuk kesalahan validasi
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $updated = Carbon::now();
        
        $user = Siswa::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // $request->validate([
        //     'name' => 'required|min:5',
        //     'birth_date' => 'date|nullable',
        //     'gender' => 'in:laki-laki,perempuan|nullable',
        //     'class' => 'nullable|string',
        //     'parent' => 'nullable|string',
        //     'phone_number' => 'nullable|string',
        //     'email' => 'email|unique:users,email,' . $id,
        //     'addres' => 'nullable|string'  
        // ]);

        $updateSuccess = $user->update([
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            // 'class' => $request->class,
            'parent' => $request->parent,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'addres' => $request->addres,  
            'kelas_id' => $request->kelas_id,  
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
                'message' => 'Update failed'
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Siswa::find($id);

        if (!$user) {
            return response()->json(['message' => 'Siswa not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'Siswa deleted successfully']);
    }
}
