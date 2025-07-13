<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKendaraanRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'nama_kendaraan'     => 'required|string|max:255',
            'id_kategori'        => 'required|exists:kategori,id_kategori',
            'plat_nomor'         => 'required|string|max:20|unique:kendaraans,plat_nomor',
            'kapasitas_muatan'   => 'required|numeric|min:0',
            'status_kendaraan'   => 'nullable|string|in:tersedia,terpakai,rusak',
        ];
    }

    public function messages()
    {
        return [
            'id_kategori.exists' => 'Kategori tidak ditemukan',
            'plat_nomor.unique'  => 'Plat nomor sudah digunakan',
            'status_kendaraan.in' => 'Status kendaraan tidak valid (tersedia, terpakai, rusak)',
        ];
    }
}
