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
        $users = User::all();

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Request Data:', $request->all());
    
        // Cek apakah data array atau tunggal
        $isArray = isset($request->users);
    
        if ($isArray) {
            // Validasi array data pengguna
            $request->validate([
                'users' => 'required|array',
                'users.*.name' => 'sometimes|string|max:255',
                'users.*.username' => 'required|min:3',
                'users.*.email' => 'required|email|unique:users,email',
                'users.*.role' => 'required|in:admin,guru,siswa',
            ]);
            $users = $request->users;
        } else {
            // Validasi data tunggal
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'username' => 'required|min:3',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:admin,guru,siswa',
            ]);
            $users = [$request->all()];
        }
    
        // Menyimpan pengguna yang berhasil diinput
        $insertedUsers = [];
        // Menyimpan pengguna yang gagal diinput
        $errors = [];
    
        // Proses penyimpanan data pengguna
        foreach ($users as $userData) {
            $password = substr($userData['email'], 0, 3) . substr($userData['username'], 0, 3);
    
            try {
                // Membuat objek User baru
                $user = new User();
                $user->name = $userData['name'] ?? null;
                $user->username = $userData['username'];
                $user->email = $userData['email'];
                $user->password = Hash::make($password);
                $user->role = $userData['role'];
    
                // Simpan user ke database
                if ($user->save()) {
                    $insertedUsers[] = $user;
                } else {
                    $errors[] = ['email' => $userData['email'], 'error' => 'Failed to insert user'];
                }
            } catch (\Exception $e) {
                $errors[] = ['email' => $userData['email'], 'error' => $e->getMessage()];
            }
        }
    
        // Kembalikan response berdasarkan hasil
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
    

    public function checkExistingUsers(Request $request)
{
    $emails = $request->emails;

    // Ambil email yang sudah ada di database
    $existingEmails = User::whereIn('email', $emails)->pluck('email')->toArray();

    return response()->json([
        'existingEmails' => $existingEmails
    ]);
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
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|string|in:admin,guru,siswa',
        ]);

        $updateData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        User::where('id', $id)->update($updateData);

        return response()->json(['success' => true, 'data' => User::find($id)]);
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
