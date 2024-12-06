<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionEssay extends Model
{
    use HasFactory;

    protected $fillable = ['essay_id', 'pertanyaan', 'jawaban'];

    public function essay()
    {
        return $this->belongsTo(Essay::class);
    }
}
