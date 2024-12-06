<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'data_kehadiran' => 'required|array',
            'data_kehadiran.*.nama' => 'required|string',
            'data_kehadiran.*.status' => 'required|string|in:hadir,sakit,izin,alpha',
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);
        $siswaList = $kelas->siswa;

        if (count($request->data_kehadiran) !== $siswaList->count()) {
            return response()->json([
                'message' => 'Jumlah data kehadiran tidak sesuai dengan jumlah siswa di kelas ini.'
            ], 400);
        }

        $absensi = Absensi::create([
            'kelas_id' => $request->kelas_id,
            'data_kehadiran' => $request->data_kehadiran,
        ]);

        return response()->json([
            'message' => 'Data absensi berhasil disimpan.',
            'data' => $absensi,
        ], 201);
    }

    /**
     * Fetch siswa based on kelas_id and return structure for attendance.
     */
    public function getSiswaByKelas($kelas_id)
    {
        $kelas = Kelas::findOrFail($kelas_id);
        $siswaList = $kelas->siswa;

        $dataKehadiran = $siswaList->map(function ($siswa) {
            return [
                'nama' => $siswa->name,
                'status' => $siswa->data_kehadiran,
            ];
        });

        return response()->json([
            'kelas' => $kelas->kelas,
            'siswa' => $dataKehadiran,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        //
    }
}
