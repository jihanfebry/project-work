<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionChoice extends Model
{
    use HasFactory;

    protected $fillable = ['question_choice_id', 'pertanyaan', 'jawaban'];


    // Relasi ke question_choice_titles

    public function title()
    {
        return $this->belongsTo(QuestionChoiceTitle::class, 'question_choice_id');
    }

    // Relasi ke question_options
    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }
}
