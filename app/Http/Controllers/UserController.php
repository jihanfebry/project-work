<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('users')->get();

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
         // Validasi input
         $request->validate([
             'username' => 'required|min:3',
             'email' => 'required|email|unique:users,email',
             'role' => 'required|in:admin,guru,siswa',
             'password' => 'required|min:3'
         ]);
     
         // Sisipkan data ke dalam tabel users
         $inserted = DB::table('users')->insert([
             'username' => $request->username,
             'email' => $request->email,
             'password' => Hash::make($request->password),
             'role' => $request->role,
         ]);
     
         // Cek hasil penyisipan dan kembalikan respons yang sesuai
         if ($inserted) {
             $user = DB::table('users')->where('email', $request->email)->first();
             return response()->json([
                 'success' => true,
                 'data' => $user
             ]);
         } else {
             return response()->json([
                 'success' => false,
                 'message' => 'Insert data failed'
             ]);
        }
    }
     
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
     
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'username' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|string|in:admin,guru,siswa',
        ]);
        
        if($request->password){
            User::where('id', $id)->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return response()->json($user);

        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}