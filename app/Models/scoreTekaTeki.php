<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class scoreTekaTeki extends Model
{
    use HasFactory;

    protected $table = 'score_teka_tekis';
    protected $fillable = [
        'user_id',
        'teka_tekis_id',
        'score',
        'exam_date',
    ];
}
