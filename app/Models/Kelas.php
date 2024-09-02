<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Kelas extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function SiswaType()
    {
        return $this->belongsTo(Siswa::class);
    }
}
