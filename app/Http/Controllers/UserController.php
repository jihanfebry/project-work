<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('users')->get();

        return response()->json([
            'data' => $data,
            'status' => 404
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

        $password = substr($request->username) . "123";

        $data= DB::table('users')->insert([
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'class' => $request->class,
            'parents' => $request->parents,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'addres' => $request->addres,
            'role' => $request->role,
            'username' => $request->username,
            'password' => Hash::make($password)
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
