<?php

namespace App\Http\Controllers;

use App\Models\QuestionChoice;
use App\Models\QuestionOption;
use App\Models\ScoreChoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScoreChoiceController extends Controller
{
    public function calculateScore(Request $request)
    {
        // Ambil pengguna yang sedang login
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak terautentikasi'], 401);
        }

        // Validasi input
        $request->validate([
            'start_time' => 'required|date',
            'choices' => 'required|array',
            'choices.*.question_id' => 'required|exists:question_choices,id',
            'choices.*.user_answer' => 'required|string',
        ]);

        // Waktu mulai dan selesai
        $startTime = Carbon::parse($request->input('start_time'));
        $endTime = Carbon::now();

        // Ambil jawaban pengguna
        $choices = $request->input('choices', []);
        $correctCount = 0;
        $incorrectCount = 0;

        foreach ($choices as $answer) {
            // Cari soal berdasarkan ID
            $question = QuestionChoice::find($answer['question_id']);
            if (!$question) {
                continue; // Lewati jika soal tidak ditemukan
            }

            // Ambil pilihan yang benar dari question_options
            $correctOption = QuestionOption::where('question_id', $answer['question_id'])
                ->where('pilihan', $question->jawaban)
                ->first();

            if ($correctOption && $correctOption->pilihan === $answer['user_answer']) {
                $correctCount++;
            } else {
                $incorrectCount++;
            }
        }

        // Hitung durasi pengerjaan
        $duration = $startTime->diffInMinutes($endTime);

        // Hitung skor akhir
        $finalScore = $correctCount * 10; // Setiap jawaban benar bernilai 10

        // Simpan skor untuk pengguna
        ScoreChoice::create([
            'user_id' => $user->id,
            'score' => $finalScore,
            'exam_date' => $endTime,
            'question_choice_id' => $choices[0]['question_id'], // Ambil ID soal pertama
        ]);

        // Kembalikan hasil dalam bentuk JSON
        return response()->json([
            'user_name' => $user->name,
            'correct_answers' => $correctCount,
            'incorrect_answers' => $incorrectCount,
            'duration' => $duration . ' minutes',
            'final_score' => $finalScore,
        ]);
    }
}
