<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePesananRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'alamat_jemput'  => 'required|string',
            'lat_jemput'     => 'required|numeric',
            'lng_jemput'     => 'required|numeric',
            'alamat_tujuan'  => 'required|string',
            'lat_tujuan'     => 'required|numeric',
            'lng_tujuan'     => 'required|numeric',
            'jarak_km'       => 'required|numeric',
            'biaya'          => 'required|numeric',
            'tanggal_jemput' => 'required|date_format:Y-m-d H:i:s',
            'id_kategori'    => 'required|integer|exists:kategori,id_kategori',
        ];
    }

    public function messages()
    {
        return [
            'alamat_jemput.required' => 'Alamat jemput wajib diisi',
            'lat_jemput.required'    => 'Latitude jemput wajib diisi',
            'lng_jemput.required'    => 'Longitude jemput wajib diisi',
            'alamat_tujuan.required' => 'Alamat tujuan wajib diisi',
            'lat_tujuan.required'    => 'Latitude tujuan wajib diisi',
            'lng_tujuan.required'    => 'Longitude tujuan wajib diisi',
            'jarak_km.required'      => 'Jarak wajib diisi',
            'biaya.required'         => 'Biaya wajib diisi',
            'tanggal_jemput.required'=> 'Tanggal jemput wajib diisi',
            'id_kategori.required'   => 'Kategori kendaraan wajib dipilih',
        ];
    }
}
