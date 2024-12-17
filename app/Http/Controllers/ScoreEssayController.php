<?php

namespace App\Http\Controllers;

use App\Models\QuestionEssay;
use App\Models\ScoreEssay;  
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScoreEssayController extends Controller
{
    public function calculateScore(Request $request)
{
    // Ambil pengguna yang sedang login
    $user = $request->user();
    if (!$user) {
        return response()->json(['error' => 'Pengguna tidak terautentikasi'], 401);
    }

    // Ambil waktu mulai dari request
    $startTime = Carbon::parse($request->input('start_time'));
    $endTime = Carbon::now();

    // Validasi input yang diterima
    $request->validate([
        'essays' => 'required|array',
        'essays.*.question_id' => 'required|exists:question_essays,id', // Pastikan question_id ada di tabel question_essays
        'essays.*.user_answer' => 'required|string', // Pastikan jawaban pengguna valid
    ]);

    // Ambil data input jawaban dari request
    $essayAnswers = $request->input('essays', []);

    // Variabel untuk menghitung jumlah jawaban benar dan salah
    $correctCount = 0;
    $incorrectCount = 0;

    // Proses setiap jawaban yang diberikan oleh pengguna
    foreach ($essayAnswers as $answer) {
        // Ambil soal berdasarkan question_id
        $question = QuestionEssay::find($answer['question_id']);
        
        // Pastikan soal ditemukan dan bandingkan jawaban dengan jawaban yang benar
        if ($question) {
            // Gunakan kolom jawaban dari tabel question_essays untuk perbandingan
            if ($question->jawaban == $answer['user_answer']) {
                $correctCount++; // Jawaban benar
            } else {
                $incorrectCount++; // Jawaban salah
            }
        }
    }

    // Hitung lama pengerjaan ujian
    $duration = $startTime->diffInMinutes($endTime);

    // Hitung skor akhir
    $finalScore = $correctCount * 10; // Setiap jawaban benar bernilai 10

    // Simpan hasil skor untuk setiap soal yang dijawab
    foreach ($essayAnswers as $answer) {
        $question = QuestionEssay::find($answer['question_id']);
        if ($question) {
            ScoreEssay::create([
                'user_id' => $user->id,
                'score' => $finalScore,
                'exam_date' => $endTime,
                'question_id' => $question->id, // Menyimpan ID soal untuk relasi
            ]);
        }
    }

    // Kembalikan response dengan detail skor dan nama pengguna
    return response()->json([
        'user_name' => $user->name, // Menambahkan nama pengguna di response JSON
        'correct_answers' => $correctCount,
        'incorrect_answers' => $incorrectCount,
        'duration' => $duration . ' minutes',
        'final_score' => $finalScore,
    ]);
}
}
