<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionChoiceTitle extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    // Relasi ke question_choices
    public function questions()
    {
        return $this->hasMany(QuestionChoice::class, 'question_choice_id');
    }
}
