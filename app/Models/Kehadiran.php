<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Siswa extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Siswa memiliki banyak Kehadiran
    public function kehadirans()
    {
        return $this->hasMany(Kehadiran::class, 'Siswa_id');
    }
}
