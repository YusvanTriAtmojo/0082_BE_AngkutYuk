<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePesananRequest;
use App\Models\Pesanan;
use App\Models\Petugas;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $pelanggan = $user->pelanggan;

            if (!$pelanggan) {
                return response()->json([
                    'message' => 'Pelanggan tidak ditemukan',
                    'status_code' => 404,
                    'data' => null,
                ], 404);
            }

            $pesanan = Pesanan::where('pelanggan_id', $pelanggan->id)
                ->with(['kategori:id_kategori,nama_kategori'])
                ->latest()
                ->get();

            $data = $pesanan->map(function ($item) {
                return [
                    'id' => $item->id,
                    'pelanggan_id' => $item->pelanggan_id,
                    'id_kategori' => $item->id_kategori,
                    'petugas_id' => $item->petugas_id,
                    'id_kendaraan' => $item->id_kendaraan,
                    'tanggal_jemput' => $item->tanggal_jemput,
                    'alamat_jemput' => $item->alamat_jemput,
                    'lat_jemput' => $item->lat_jemput,
                    'lng_jemput' => $item->lng_jemput,
                    'alamat_tujuan' => $item->alamat_tujuan,
                    'lat_tujuan' => $item->lat_tujuan,
                    'lng_tujuan' => $item->lng_tujuan,
                    'jarak_km' => $item->jarak_km,
                    'biaya' => $item->biaya,
                    'status' => $item->status,
                    'foto_bukti_selesai' => $item->foto_bukti_selesai
                        ? asset('storage/' . $item->foto_bukti_selesai)
                        : null,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'kategori' => $item->kategori,
                ];
            });

            return response()->json([
                'message' => $data->isEmpty() ? 'Tidak ada data pesanan' : 'Data pesanan berhasil diambil',
                'status_code' => 200,
                'data' => $data,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
                'data' => null,
            ], 500);
        }
    }

    public function index_petugas(Request $request)
    {
        try {
            $user = auth()->user();
            $petugas = $user->petugas;

            if (!$petugas) {
                return response()->json([
                    'message' => 'Petugas tidak ditemukan',
                    'status_code' => 404,
                    'data' => null,
                ], 404);
            }

            $data = Pesanan::where('petugas_id', $petugas->id)
                ->with(['kategori:id_kategori,nama_kategori', 'pelanggan:id,nama_pelanggan', 'kendaraan:id_kendaraan,nama_kendaraan']) 
                ->latest()
                ->get();

            return response()->json([
                'message' => $data->isEmpty() ? 'Tidak ada data pesanan untuk petugas ini' : 'Data pesanan berhasil diambil',
                'status_code' => 200,
                'data' => $data,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
                'data' => null,
            ], 500);
        }
    }


    public function all()
    {
        try {
            $data = Pesanan::with([
                'kategori:id_kategori,nama_kategori',
                'pelanggan:id,nama_pelanggan'
            ])->latest()->get();

            return response()->json([
                'message' => $data->isEmpty() ? 'Tidak ada data pesanan' : 'Data pesanan berhasil diambil',
                'status_code' => 200,
                'data' => $data,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null,
            ], 500);
        }
    }

    public function getpesananid($id)
    {
        try {
            $pesanan = Pesanan::with([
                'kategori:id_kategori,nama_kategori',
                'pelanggan:id,nama_pelanggan',
                'petugas:id,nama_petugas',
                'kendaraan:id_kendaraan,nama_kendaraan,plat_nomor'
            ])->find($id);

            if (!$pesanan) {
                return response()->json([
                    'message' => 'Pesanan tidak ditemukan',
                    'status_code' => 404,
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'message' => 'Detail pesanan berhasil diambil',
                'status_code' => 200,
                'data' => $pesanan,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
                'data' => null,
            ], 500);
        }
    }

    public function store(StorePesananRequest $request)
    {
        try {
            $data = $request->validated();
            $data['pelanggan_id'] = Auth::user()->pelanggan->id;

            $pesanan = Pesanan::create($data);

            return response()->json([
                'message' => 'Pesanan berhasil dibuat',
                'status_code' => 201,
                'data' => $pesanan,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
                'data' => null,
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $pesanan = Pesanan::find($id);

            if (!$pesanan) {
                return response()->json([
                    'message' => 'Pesanan tidak ditemukan',
                    'status_code' => 404,
                ], 404);
            }

            $pesanan->update($request->only([
                'status',
                'petugas_id',
                'id_kendaraan',
            ]));

            if ($request->filled('petugas_id') && $request->filled('status_petugas')) {
                Petugas::where('id', $request->input('petugas_id'))->update([
                    'status_petugas' => $request->input('status_petugas'),
                ]);
            }

            if ($request->filled('id_kendaraan') && $request->filled('status_kendaraan')) {
                Kendaraan::where('id_kendaraan', $request->input('id_kendaraan'))->update([
                    'status_kendaraan' => $request->input('status_kendaraan'),
                ]);
            }

            return response()->json([
                'message' => 'Pesanan berhasil diperbarui',
                'status_code' => 200,
                'data' => $pesanan,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function uploadBuktiSelesai(Request $request, $id)
    {
        try {
            $pesanan = Pesanan::find($id);

            if (!$pesanan) {
                return response()->json([
                    'message' => 'Pesanan tidak ditemukan',
                    'status_code' => 404,
                ], 404);
            }

            $request->validate([
                'foto_bukti_selesai' => 'required|image|mimes:jpeg,jpg,png',
            ]);

            $path = $request->file('foto_bukti_selesai')->store('bukti_selesai', 'public');

            $pesanan->update([
                'foto_bukti_selesai' => $path,
                'status' => 'selesai',
            ]);
            
            if ($pesanan->petugas_id) {
                $petugas = Petugas::find($pesanan->petugas_id);
                if ($petugas) {
                    $petugas->update(['status_petugas' => 'tersedia']);
                }
            }

            if ($pesanan->id_kendaraan) {
                $kendaraan = Kendaraan::find($pesanan->id_kendaraan);
                if ($kendaraan) {
                    $kendaraan->update(['status_kendaraan' => 'tersedia']);
                }
            }

            return response()->json([
                'message' => 'Bukti selesai berhasil diunggah',
                'status_code' => 200,
                'data' => [
                    'id' => $pesanan->id,
                    'foto_bukti_selesai' => asset('storage/' . $pesanan->foto_bukti_selesai),
                    'status' => $pesanan->status,
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pesanan = Pesanan::find($id);

            if (!$pesanan) {
                return response()->json([
                    'message' => 'Pesanan tidak ditemukan',
                    'status_code' => 404,
                ], 404);
            }

            if (strtolower($pesanan->status) == 'selesai') {
                return response()->json([
                    'message' => 'Pesanan hanya bisa dihapus jika masih pending',
                    'status_code' => 403,
                ], 403);
            }

            $pesanan->delete();

            return response()->json([
                'message' => 'Pesanan berhasil dihapus',
                'status_code' => 200,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }
}
