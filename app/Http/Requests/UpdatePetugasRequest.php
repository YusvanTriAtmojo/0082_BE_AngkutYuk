<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetugasRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'nama_petugas'    => 'sometimes|string|max:20',
            'notlp_petugas'   => 'sometimes|string|max:13',
            'alamat_petugas'  => 'sometimes|string',
        ];
    }
}

