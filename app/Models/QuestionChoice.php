<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionChoice extends Model
{
    use HasFactory;

    protected $fillable = ['question_text'];

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

}
