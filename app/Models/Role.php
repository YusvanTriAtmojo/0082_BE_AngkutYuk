<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['nama_role'];
    protected $table = 'roles';
    public function users()
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }
   
}
