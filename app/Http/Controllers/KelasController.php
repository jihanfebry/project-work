<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = DB::table('kelas')->get();

        return response()->json([
            'data' => $data
        ]);
        // $data = DB::table('kelas')
        // ->join('siswas', 'siswas.kelas_id', '=', 'kelas.id')
        // ->select('kelas.id', 'kelas.kelas', DB::raw('GROUP_CONCAT(siswas.name) as name_siswa'))
        // ->groupBy('kelas.id', 'kelas.kelas')
        // ->get();

        // $data = $data->map(function($item) {
        //     $item->name_siswa = explode(',', $item->name_siswa);
        //     return $item;
        // });

        // return response()->json([
        //     'data' => $data
        // ]);
    }

    public function listSiswaByKelas()
    {
        $data = DB::table('kelas')
            ->join('siswas', 'siswas.kelas_id', '=', 'kelas.id')
            ->select('kelas.id', 'kelas.kelas', 'siswas.name as name_siswa')
            ->get();
        // dd($data);
        $response = $data->map(function($item) {
            return [
                'id' => $item->id,
                'kelas' => $item->kelas,
                'name_siswa' => $item->name_siswa
            ];
        });

        return response()->json([
            'data' => $response
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
        $data= DB::table('kelas')->insert([
            'kelas' => $request->kelas
        ]);

        if ($data) {
            return response()->json([
                'suscces' => true,
                'data' => $data
            ]); 
        }else{
            return response()->json([
                'suscces' => false,
                'message' => 'Update data failed'
            ], 403);
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kelas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
    {
        //
    }
}
