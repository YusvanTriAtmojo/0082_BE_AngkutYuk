<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraans'; 
    protected $primaryKey = 'id_kendaraan';
    public $timestamps = false;

    protected $fillable = [
        'nama_kendaraan',
        'id_kategori',
        'plat_nomor',
        'kapasitas_muatan',
        'status_kendaraan',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}
