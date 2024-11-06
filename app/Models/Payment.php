<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments'; // Jika nama tabel tidak mengikuti konvensi Laravel, Anda perlu menyebutkannya

    protected $fillable = [
        'user_id',
        'receipt_image',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
