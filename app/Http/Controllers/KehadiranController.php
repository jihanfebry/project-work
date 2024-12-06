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
            ->join('siswas', 'absensi.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'absensi.kelas_id', '=', 'kelas.id')
            ->select('absensi.id', 'absensi.absen', 'siswas.name as nama', 'kelas.kelas as kelas')
            // ->whereDate('absensi.created_at', '=', $request->date)
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

        $kehadiran = DB::table('kehadirans')->where('siswa_id', '=', $user->id)->first();

        if ($kehadiran) {
            $updateSuccess = DB::table('kehadirans')
                ->where('id', '=', $kehadiran->id)
                ->update([
                    'absen' => $request->absen,
                    'updated_at' => $created
                ]);
        } else {
            $kelas_id = DB::table('siswas')
                ->where('id', '=', $user->id)
                ->value('kelas_id');
            
            if (!$kelas_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan untuk siswa ini'
                ]);
            }

            $updateSuccess = DB::table('kehadirans')->insert([
                'siswa_id' => $user->id,
                'kelas_id' => $kelas_id,
                'absen' => $request->absen,
                'created_at' => $created
            ]);
        }

        if ($updateSuccess) {
            $kelas = DB::table('kelas')
                ->where('id', $user->kelas_id)
                ->value('kelas');

            $kelas = $kelas ?: 'Kelas tidak ditemukan';

            return response()->json([
                'success' => 'Update kehadiran success',
                'data' => [
                    'id' => $user->id,
                    'nama' => $user->name,
                    'kelas' => $kelas,
                    'kehadiran' => $request->absen
                ]
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
