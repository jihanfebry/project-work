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
        \Log::info('Request Data:', $request->all());
    
        // Periksa apakah data yang dikirim adalah array dari pengguna
        $isArray = isset($request->users);
    
        if ($isArray) {
            // Validasi array data pengguna
            $data = $request->validate([
                'users.*.name' => 'sometimes|string|max:255',
                'users.*.username' => 'required|min:3',
                'users.*.email' => 'required|email|unique:users,email',
                'users.*.role' => 'required|in:admin,guru,siswa',
            ]);
    
            $users = $data['users'];
        } else {
            // Validasi data tunggal
            $data = $request->validate([
                'name' => 'sometimes|string|max:255',
                'username' => 'required|min:3',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:admin,guru,siswa',
            ]);
    
            $users = [$data];
        }
    
        $insertedUsers = [];
        $errors = [];
    
        foreach ($users as $userData) {
            $password = substr($userData['email'], 0, 3) . substr($userData['username'], 0, 3);
    
            try {
                $user = new User();
                $user->name = $userData['name'] ?? null;
                $user->username = $userData['username'];
                $user->email = $userData['email'];
                $user->password = Hash::make($password);
                $user->role = $userData['role'];
    
                if ($user->save()) {
                    $insertedUsers[] = $user;
                } else {
                    $errors[] = ['email' => $userData['email'], 'error' => 'Failed to insert user'];
                }
            } catch (\Exception $e) {
                $errors[] = ['email' => $userData['email'], 'error' => $e->getMessage()];
            }
        }
    
        if (empty($errors)) {
            return response()->json([
                'success' => true,
                'data' => $insertedUsers
            ]);
        } else {
            return response()->json([
                'success' => false,
                'errors' => $errors
            ], 400);
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
        'username' => 'sometimes|string|max:255|unique:users,username,' . $id,
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