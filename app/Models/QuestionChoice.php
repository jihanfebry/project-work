<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionChoice extends Model
{
    use HasFactory;

    protected $fillable = [
    'pertanyaan', 
    'question_choice_id', 
    'jawaban'
];

    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_choice_id', 'id');
    }

    public function scoreChoice()
    {
        return $this->hasMany(QuestionChoice::class);
    }
}