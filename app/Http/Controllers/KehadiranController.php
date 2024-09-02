<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use App\Models\Kehadiran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = DB::table('kehadirans as absensi')
            ->join('siswas', 'absensi.Siswa_id', '=', 'siswas.id')
            ->select('absensi.*', 'siswas.name')
            ->whereDate('absensi.created_at', '=', $request->date)
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Kehadiran $kehadiran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kehadiran $kehadiran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $created = Carbon::now()->format('Y-m-d');

        $user = Siswa::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'absen' => 'in:Hadir,Izin,Sakit,Alpa'
        ]);

        $kehadiran = DB::table('kehadirans')->where('Siswa_id', '=', $user->id)->first();

        if ($kehadiran) {
            // Update kehadiran jika sudah ada
            $updateSuccess = DB::table('kehadirans')
                ->where('id', '=', $kehadiran->id)
                ->update([
                    'absen' => $request->absen,
                    'updated_at' => $created
                ]);
        } else {
            // Insert kehadiran baru jika belum ada
            $updateSuccess = DB::table('kehadirans')->insert([
                'Siswa_id' => $user->id, // Pastikan Siswa_id diberikan saat insert
                'absen' => $request->absen,
                'created_at' => $created
            ]);
        }

        if ($updateSuccess) {
            return response()->json([
                'success' => 'update kehadiran success',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Update kehadiran failed'
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kehadiran $kehadiran)
    {
        //
    }
}
