<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKendaraanRequest;
use App\Http\Requests\UpdateKendaraanRequest;
use App\Models\Kendaraan;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Exception;

class KendaraanController extends Controller
{
    public function index()
    {
        try {
            $data = Kendaraan::with('kategori')->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada data kendaraan',
                    'status_code' => 200,
                    'data' => [],
                ], 200);
            }

            $formattedData = $data->map(function ($item) {
                return [
                    'id_kendaraan' => $item->id_kendaraan,
                    'nama_kendaraan' => $item->nama_kendaraan,
                    'id_kategori' => $item->id_kategori,
                    'nama_kategori' => $item->kategori->nama_kategori ?? null,
                    'plat_nomor' => $item->plat_nomor,
                    'kapasitas_muatan' => $item->kapasitas_muatan,
                    'status_kendaraan' => $item->status_kendaraan,
                ];
            });

            return response()->json([
                'message' => 'Data kendaraan berhasil diambil',
                'status_code' => 200,
                'data' => $formattedData,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function store(StoreKendaraanRequest $request)
    {
        try {
            $kategori = Kategori::find($request->id_kategori);

            if (!$kategori) {
                return response()->json([
                    'message' => 'Kategori tidak ditemukan',
                    'status_code' => 404,
                ], 404);
            }

            $kendaraan = new Kendaraan();
            $kendaraan->nama_kendaraan = $request->nama_kendaraan;
            $kendaraan->id_kategori = $request->id_kategori;
            $kendaraan->plat_nomor = $request->plat_nomor;
            $kendaraan->kapasitas_muatan = $request->kapasitas_muatan;
            $kendaraan->status_kendaraan = $request->status_kendaraan ?? 'tersedia';
            $kendaraan->save();

            return response()->json([
                'message' => 'Kendaraan berhasil ditambahkan',
                'status_code' => 201,
                'data' => [
                    'id_kendaraan' => $kendaraan->id_kendaraan,
                    'nama_kendaraan' => $kendaraan->nama_kendaraan,
                    'id_kategori' => $kendaraan->id_kategori,
                    'plat_nomor' => $kendaraan->plat_nomor,
                    'kapasitas_muatan' => $kendaraan->kapasitas_muatan,
                    'status_kendaraan' => $kendaraan->status_kendaraan,
                    
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function update(UpdateKendaraanRequest $request, $id_kendaraan)
    {
        try {
            $kendaraan = Kendaraan::find($id_kendaraan);

            if (!$kendaraan) {
                return response()->json([
                    'message' => 'Kendaraan tidak ditemukan',
                    'status_code' => 404,
                ], 404);
            }

            if ($request->has('id_kategori')) {
                $kategori = Kategori::find($request->id_kategori);
                if (!$kategori) {
                    return response()->json([
                        'message' => 'Kategori tidak ditemukan',
                        'status_code' => 404,
                    ], 404);
                }
                $kendaraan->id_kategori = $request->id_kategori;
            }

            $kendaraan->update($request->validated());

            return response()->json([
                'message' => 'Kendaraan berhasil diubah',
                'status_code' => 200,
                'data' => [
                    'id_kendaraan' => $kendaraan->id_kendaraan,
                    'nama_kendaraan' => $kendaraan->nama_kendaraan,
                    'id_kategori' => $kendaraan->id_kategori,
                    'plat_nomor' => $kendaraan->plat_nomor,
                    'kapasitas_muatan' => $kendaraan->kapasitas_muatan,
                    'status_kendaraan' => $kendaraan->status_kendaraan,
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
            $kendaraan = Kendaraan::find($id);

            if (!$kendaraan) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Kendaraan tidak ditemukan',
                ], 404);
            }

            $kendaraan->delete();

            return response()->json([
                'message' => 'Kendaraan berhasil dihapus',
                'status_code' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
