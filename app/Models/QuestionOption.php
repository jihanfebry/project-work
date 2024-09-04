<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = ['question_choice_id', 'pilihan'];

    public function questionChoice()
    {
        return $this->belongsTo(QuestionChoice::class);
    }
}
