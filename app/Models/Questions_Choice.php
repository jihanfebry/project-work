<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions_Choice extends Model
{
    use HasFactory;

    protected $table = 'choices';

    protected $fillable = [
        'question_id',
        'choice_desc',
        'true_answer',
    ];

    public function question()
    {
        return $this->belongsTo(TryoutQuestion::class, 'question_id');
    }

}
