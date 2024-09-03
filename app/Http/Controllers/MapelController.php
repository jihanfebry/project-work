<?php

namespace App\Http\Controllers;

use App\Models\mapel;
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
        ], 201);
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
        $data= DB::table('mapels')->insert([
            'material' => $request->material,
            'task' => $request->task,
            'answer' => $request->answer
        ]);

        if ($data) {
            return response()->json([
                'suscces' => true,
                'data' => $data
            ]); 
        }else{
            return response()->json([
                'suscces' => false
            ], 403);
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(mapel $mapel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(mapel $mapel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, mapel $mapel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(mapel $mapel)
    {
        //
    }
}
