<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LoginAuthController extends Controller
{

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // $user = $request->only(['username', 'password']);
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken('login token')->plainTextToken;

        // if (Auth::attempt($user)) {
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'Login berhasil'
        //     ]);
        // } else {
        //     return response()->json([
        //         'status' => 'failed',
        //         'message' => 'Proses login gagal, silahkan coba kembali dengan data yang benar!'
        //     ]);
        // }
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }

}
