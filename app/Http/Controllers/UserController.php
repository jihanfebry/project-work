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
    


    public function store(Request $request)
    {
        \Log::info('Request Data:', $request->all());

        // Periksa apakah data yang dikirim adalah array dari pengguna
        $isArray = isset($request->users);

        if ($isArray) {
            // Validasi array data pengguna
            $data = $request->validate([
                'users.*.name' => 'required|string|max:255',
                'users.*.username' => 'required|min:3',
                'users.*.email' => 'required|email|unique:users,email',
                'users.*.role' => 'required|in:admin,guru,siswa',
            ]);

            $users = $data['users'];
        } else {
            // Validasi data tunggal
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|min:3',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:admin,guru,siswa',
            ]);

            $users = [$data];
        }

        $insertedUsers = [];
        $errors = [];

        foreach ($users as $user) {
            $password = substr($user['email'], 0, 3) . substr($user['username'], 0, 3);

            try {
                $inserted = DB::table('users')->insertGetId([
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'password' => Hash::make($password),
                    'role' => $user['role'],
                ]);
                
                if ($inserted) {
                    if ($user['role'] === 'siswa') {
                        // Jika role adalah siswa, insert ke tabel siswas
                        $inserted_siswa = DB::table('siswas')->insert([
                            'name' => $user['name'],
                            'user_id' => $inserted
                        ]);
                    } elseif ($user['role'] === 'guru') {
                        // Jika role adalah guru, insert ke tabel gurus
                        $inserted_guru = DB::table('gurus')->insert([
                            'name' => $user['name'],
                            'user_id' => $inserted
                        ]);
                    }
                
                    // Tambahkan user yang baru saja dimasukkan ke dalam array $insertedUsers
                    $insertedUsers[] = DB::table('users')->where('email', $user['email'])->first();
                } else {
                    // Jika gagal insert ke tabel 'users', masukkan error ke dalam array $errors
                    $errors[] = ['email' => $user['email'], 'error' => 'Failed to insert user'];
                }
            } catch (\Exception $e) {
                $errors[] = ['email' => $user['email'], 'error' => $e->getMessage()];
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
        
        // Mengupdate data pengguna
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

    /**
     * Update the specified resource in storage.
     */
    
}