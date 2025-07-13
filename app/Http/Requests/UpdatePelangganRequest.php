<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePelangganRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'nama_pelanggan'   => 'sometimes|string|max:255',
            'notlp_pelanggan'  => 'sometimes|string|max:20',
            'alamat_pelanggan' => 'sometimes|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'nama_pelanggan.string'   => 'Nama pelanggan harus berupa teks.',
            'notlp_pelanggan.string'  => 'Nomor telepon harus berupa teks.',
            'alamat_pelanggan.string' => 'Alamat pelanggan harus berupa teks.',
        ];
    }
}
