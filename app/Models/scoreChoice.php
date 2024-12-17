<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class scoreChoice extends Model
{
    use HasFactory;

    protected $table = 'score_choices';
    protected $fillable = [
        'user_id',
        'score',
        'exam_date',
        'question_choice_id'
    ];

    public function QuestionChoice()
        {
            return $this->belongsTo(QuestionChoice::class);
        }

    public function QuestionOption()
        {
            return $this->belongsTo(QuestionOption::class);
        }
}
