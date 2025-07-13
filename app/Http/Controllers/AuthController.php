<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
   
    public function register(RegisterRequest $request)
    {
        try {
            $user = new User();
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->password = Hash::make($request->password);
            $user->id_role  = 3; 
            $user->save();

            $user->pelanggan()->create([
                'nama_pelanggan'   => $request->name,
                'notlp_pelanggan'  => $request->notlp_pelanggan,
                'alamat_pelanggan' => $request->alamat_pelanggan,
            ]);

            return response()->json([
                'status_code' => 201,
                'message'     => 'User created successfully',
                'data'        => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'id_role' => $user->id_role
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message'     => $e->getMessage(),
                'data'        => null
            ], 500);
        }
    }

   
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah',
                'status_code' => 401,
                'data' => null
            ], 401);
        }

        try {
            $user = Auth::guard('api')->user();

            $formatedUser = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->nama_role,
                'token' => $token
            ];

            return response()->json([
                'message' => 'Login berhasil',
                'status_code' => 200,
                'data' => $formatedUser
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ], 500);
        }
    }

    public function me()
    {
        try {
            $user = Auth::guard('api')->user();

            return response()->json([
                'message'     => 'User ditemukan',
                'status_code' => 200,
                'data'        => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role->nama_role,
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message'     => $e->getMessage(),
                'status_code' => 500,
                'data'        => null
            ], 500);
        }
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'message'     => 'Logout berhasil',
            'status_code' => 200,
            'data'        => null
        ], 200);
    }
}
