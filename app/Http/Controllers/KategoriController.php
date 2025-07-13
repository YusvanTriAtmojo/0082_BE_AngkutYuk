<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriRequest;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Exception;

class KategoriController extends Controller
{
    public function index()
    {
        try {
            $data = Kategori::all();

            if ($data->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada data kategori',
                    'status_code' => 200,
                    'data' => [],
                ], 200);
            }

            $formattedData = $data->map(function ($item) {
                return [
                    'id_kategori' => $item->id_kategori,
                    'nama_kategori' => $item->nama_kategori,
                ];
            });

            return response()->json([
                'message' => 'Data kategori berhasil diambil',
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

    public function store(StoreKategoriRequest $request)
    {
        try {
            $kategori = new Kategori();
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->save();

            return response()->json([
                'message' => 'Kategori berhasil ditambahkan',
                'status_code' => 201,
                'data' => [
                    'id_kategori' => $kategori->id_kategori,
                    'nama_kategori' => $kategori->nama_kategori,
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

    public function destroy($id)
    {
        try {
            $kategori = Kategori::find($id);

            if (!$kategori) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Kategori tidak ditemukan',
                ], 404);
            }

            $kategori->delete();

            return response()->json([
                'message' => 'Kategori berhasil dihapus',
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