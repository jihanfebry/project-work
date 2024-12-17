<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class scoreEssay extends Model
{
    use HasFactory;

    protected $table = 'score_essays';
    protected $fillable = [
        'user_id',
        'question_id',
        'score',
        'exam_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function QuestionEssay()
    {
        return $this->belongsTo(QuestionEssay::class);
    }

    public function TekaTeki()
    {
        return $this->belongsTo(TekaTeki::class);
    }
}
