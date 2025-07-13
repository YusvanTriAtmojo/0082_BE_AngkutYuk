<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePetugasRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'email'        => 'required|string|email|unique:users,email',
            'password'        => 'required|string|min:6',
            'nama_petugas'    => 'required|string|max:20',
            'notlp_petugas'   => 'required|string|max:13',
            'alamat_petugas'  => 'required|string',
            'status_petugas'  => 'nullable|string|in:tersedia,bertugas',
        ];
    }

    public function messages()
    {
        return [
            'email.required'       => 'email wajib diisi',
            'email.unique'         => 'email sudah digunakan',
            'password.required'       => 'Password wajib diisi',
            'password.min'            => 'Password minimal 6 karakter',
            'nama_petugas.required'   => 'Nama petugas wajib diisi',
            'notlp_petugas.required'  => 'Nomor telepon wajib diisi',
            'alamat_petugas.required' => 'Alamat wajib diisi',
            'status_petugas.in'       => 'Status petugas hanya boleh tersedia atau bertugas',
        ];
    }
}

