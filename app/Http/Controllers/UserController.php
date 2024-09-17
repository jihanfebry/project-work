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
        // Mengambil semua data user menggunakan model
        $users = User::all();

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Method tidak diperlukan jika menggunakan API
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'username' => 'required|min:3',
    //         'email' => 'required|email|unique:users,email',
    //         'role' => 'required|in:admin,guru,siswa',
    //         'password' => 'required|min:3'
    //     ]);

    //     // Membuat user baru menggunakan model
    //     $user = User::create([
    //         'name' => $request->name,
    //         'username' => $request->username,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => $request->role,
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'data' => $user
    //     ]);
    // }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'sometimes|string|min:8',
            'role' => 'required|in:admin,guru,siswa',
        ]);
    
        // Membuat password berdasarkan kombinasi email dan username
        $password = substr($request->email, 0, 3) . substr($request->username, 0, 3);
    
        // Menggunakan model User untuk menyimpan data baru
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($password);
        $user->role = $request->role;
    
        if ($user->save()) {
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } else {
            return response()->json([
                'success' => false
            ], 400); // Status HTTP 400 untuk kesalahan validasi atau request
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Cek apakah user ada
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validasi input
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|string|in:admin,guru,siswa',
        ]);

        // Update field yang ada dalam request
        $user->update([
            'name' => $request->name ?? $user->name,
            'username' => $request->username ?? $user->username,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role ?? $user->role,
        ]);

        return response()->json(['message' => 'User updated successfully', 'data' => $user]);
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