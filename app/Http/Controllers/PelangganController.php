<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePelangganRequest;
use Illuminate\Support\Facades\Log;

class PelangganController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = auth()->user();
        $pelanggan = $user->pelanggan;

        if (!$pelanggan) {
            return response()->json([
                'message' => 'Pelanggan tidak ditemukan',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'Data pelanggan berhasil diambil',
            'status_code' => 200,
            'data' => [
                'id' => $pelanggan->id,
                'user_id' => $pelanggan->user_id,
                'nama_pelanggan' => $pelanggan->nama_pelanggan,
                'notlp_pelanggan' => $pelanggan->notlp_pelanggan,
                'alamat_pelanggan' => $pelanggan->alamat_pelanggan,
                'foto_profile' => $pelanggan->foto_profile ? asset('storage/' . $pelanggan->foto_profile) : null,
                'user' => [
                    'email' => $pelanggan->user->email,
                ],
            ]
        ]);
    }

    public function updateByUserId(UpdatePelangganRequest $request)
    {
        $user = auth()->user();
        $pelanggan = $user->pelanggan;

        if (!$pelanggan) {
            return response()->json([
                'message' => 'Pelanggan tidak ditemukan',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }

        $pelanggan->update($request->validated());

        return response()->json([
            'message' => 'Data pelanggan berhasil diperbarui',
            'status_code' => 200,
            'data' => [
                'id' => $pelanggan->id,
                'user_id' => $pelanggan->user_id,
                'nama_pelanggan' => $pelanggan->nama_pelanggan,
                'notlp_pelanggan' => $pelanggan->notlp_pelanggan,
                'alamat_pelanggan' => $pelanggan->alamat_pelanggan,
            ],
        ]);
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $user = auth()->user();
        $pelanggan = $user->pelanggan;

        if (!$pelanggan) {
            return response()->json([
                'message' => 'Pelanggan tidak ditemukan',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }

        if ($request->hasFile('foto')) {
            try {
                $path = $request->file('foto')->store('foto_profile', 'public');
                $pelanggan->foto_profile = $path;
                $pelanggan->save();

                return response()->json([
                    'message' => 'Foto profil berhasil diperbarui',
                    'status_code' => 200,
                    'data' => [
                        'foto_profile' => asset('storage/' . $path)
                    ]
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'status_code' => 500,
                ], 500);
            }
        }

        return response()->json([
            'message' => 'Tidak ada file yang dikirim',
            'status_code' => 400,
        ], 400);
    }
}
