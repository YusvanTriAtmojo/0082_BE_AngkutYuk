<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePetugasRequest;
use App\Http\Requests\UpdatePetugasRequest;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Exception;

class PetugasController extends Controller
{
    public function index()
    {
        try {
            $data = Petugas::with('user')->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada data petugas',
                    'status_code' => 200,
                    'data' => [],
                ], 200);
            }

            $formattedData = $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'nama_petugas' => $item->nama_petugas,
                    'notlp_petugas' => $item->notlp_petugas,
                    'alamat_petugas' => $item->alamat_petugas,
                    'status_petugas' => $item->status_petugas,
                ];
            });

            return response()->json([
                'message' => 'Data petugas berhasil diambil',
                'status_code' => 200,
                'data' => $formattedData,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function store(StorePetugasRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->nama_petugas,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $petugas = Petugas::create([
                'user_id' => $user->id,
                'nama_petugas' => $request->nama_petugas,
                'notlp_petugas' => $request->notlp_petugas,
                'alamat_petugas' => $request->alamat_petugas,
                'status_petugas' => $request->status_petugas ?? 'tersedia',
            ]);

            return response()->json([
                'message' => 'Petugas berhasil ditambahkan',
                'status_code' => 201,
                'data' => [
                    'id' => $petugas->id,
                    'user_id' => $petugas->user_id,
                    'nama_petugas' => $petugas->nama_petugas,
                    'notlp_petugas' => $petugas->notlp_petugas,
                    'alamat_petugas' => $petugas->alamat_petugas,
                    'status_petugas' => $petugas->status_petugas,
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function update(UpdatePetugasRequest $request, $id)
    {
        try {
            $petugas = Petugas::find($id);

            if (!$petugas) {
                return response()->json([
                    'message' => 'Petugas tidak ditemukan',
                    'status_code' => 404,
                ], 404);
            }

            $petugas->update($request->validated());

            return response()->json([
                'message' => 'petugas berhasil diubah',
                'status_code' => 200,
                'data' => [
                    'id' => $petugas->id,
                    'nama_petugas' => $petugas->nama_petugas,
                    'notlp_petugas' => $petugas->notlp_petugas,
                    'alamat_petugas' => $petugas->alamat_petugas,
                    'status_petugas' => $petugas->status_petugas,
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $petugas = Petugas::find($id);

            if (!$petugas) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Petugas tidak ditemukan',
                ], 404);
            }

            $petugas->delete();

            return response()->json([
                'message' => 'Petugas berhasil dihapus',
                'status_code' => 200,
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getProfile(Request $request)
    {
        $user = auth()->user();
        $petugas = $user->petugas;

        if (!$petugas) {
            return response()->json([
                'message' => 'Petugas tidak ditemukan',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'Data petugas berhasil diambil',
            'status_code' => 200,
            'data' => [
                'id' => $petugas->id,
                'user_id' => $petugas->user_id,
                'nama_petugas' => $petugas->nama_petugas,
                'notlp_petugas' => $petugas->notlp_petugas,
                'alamat_petugas' => $petugas->alamat_petugas,
                'user' => [
                    'email' => $petugas->user->email,
                ],
            ]
        ]);
    }
}
