<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TekaTeki extends Model
{
    use HasFactory;

    protected $fillable = ['gambar', 'jawaban', 'clue'];

    // Method untuk menghasilkan clue
    public static function generateClue($jawaban)
    {
        $clue = '';
        for ($i = 0; $i < strlen($jawaban); $i++) {
            if ($i % 2 == 0) {
                $clue .= strtoupper($jawaban[$i]);
            } else {
                $clue .= '_';
            }
        }
        return $clue;
    }
}
