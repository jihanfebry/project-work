<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id', 
        'question_image_id',
        'answer_text', 
        'is_correct',
        'question_text'
    ];

    // Relasi ke QuestionEssay, menggunakan question_image_id sebagai foreign key ke id dari QuestionEssay
    public function questionEssay()
    {
        return $this->belongsTo(QuestionEssay::class, 'question_image_id', 'id');
    }

    // Mendapatkan question_image melalui relasi
    public function getQuestionImageAttribute()
    {
        return $this->questionEssay->question_image;
    }
}
