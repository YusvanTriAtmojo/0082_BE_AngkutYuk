<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateKendaraanRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        $id = $this->route('id_kendaraan'); 

        return [
            'nama_kendaraan'     => 'sometimes|string|max:255',
            'id_kategori'        => 'sometimes|exists:kategori,id_kategori',
            'plat_nomor'         => 'sometimes|string|max:20|unique:kendaraans,plat_nomor,' . $id . ',id_kendaraan',
            'kapasitas_muatan'   => 'sometimes|numeric|min:0',
            'status_kendaraan'   => 'nullable|string|in:tersedia,terpakai,rusak',
        ];
    }

    public function messages()
    {
        return [
            'id_kategori.exists'   => 'Kategori tidak ditemukan',
            'plat_nomor.unique'    => 'Plat nomor sudah digunakan',
            'status_kendaraan.in'  => 'Status kendaraan tidak valid (tersedia, terpakai, rusak)',
        ];
    }
}
