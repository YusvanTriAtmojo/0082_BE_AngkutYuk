<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggans';

    protected $fillable = [
        'user_id',
        'nama_pelanggan',
        'notlp_pelanggan',
        'alamat_pelanggan',
        'foto_profile',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
