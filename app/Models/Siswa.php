<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Siswa extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function KehadiranType()
    {
        return $this->belongsTo(Kehadiran::class);
    }
    public function KelasType()
    {
        return $this->hasMany(Kelas::class);
    }
    public function UserType()
    {
        return $this->hasMany(User::class);
    }
}
