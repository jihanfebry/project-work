<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['pertanyaan', 'jawaban', 'question_choice_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi ke QuestionChoice
     */
    public function questionChoice()
    {
        return $this->belongsTo(QuestionChoice::class, 'question_choice_id');
    }

    /**
     * Relasi ke QuestionOptions
     */
    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }
}
