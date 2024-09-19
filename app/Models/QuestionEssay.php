<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionEssay extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_image'
    ];

    protected $casts = [
        'question_image' => 'array',
    ];

    // Relasi ke EssayAnswer, menghubungkan id dari QuestionEssay dengan question_image_id di EssayAnswer
    public function essayAnswers()
    {
        return $this->hasMany(EssayAnswer::class, 'question_image_id', 'id');
    }
}
