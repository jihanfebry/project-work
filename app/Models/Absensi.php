<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = ['kelas_id', 'data_kehadiran'];

    protected $casts = [
        'data_kehadiran' => 'array'
    ];

    public function KelasType()
    {
        return $this->belongsTo(Kelas::class);
    }
}
