<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;
    protected $table = 'petugass';

    protected $fillable = [
        'user_id',
        'nama_petugas',
        'notlp_petugas',
        'alamat_petugas',
        'status_petugas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
