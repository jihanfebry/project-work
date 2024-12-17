<?php

namespace App\Http\Controllers;

use App\Models\TekaTeki;
use App\Models\ScoreTekaTeki;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScoreTekaTekiController extends Controller
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
            'answers' => 'required|array',
            'answers.*.teka_teki_id' => 'required|exists:teka_tekis,id',
            'answers.*.user_answer' => 'required|string',
        ]);

        // Waktu mulai dan selesai
        $startTime = Carbon::parse($request->input('start_time'));
        $endTime = Carbon::now();

        // Ambil jawaban pengguna
        $answers = $request->input('answers', []);
        $correctCount = 0;
        $incorrectCount = 0;

        foreach ($answers as $answer) {
            // Cari teka-teki berdasarkan ID
            $tekaTeki = TekaTeki::find($answer['teka_teki_id']);
            if (!$tekaTeki) {
                continue; // Lewati jika teka-teki tidak ditemukan
            }

            // Periksa jawaban
            if (strtolower(trim($tekaTeki->jawaban)) === strtolower(trim($answer['user_answer']))) {
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
        ScoreTekaTeki::create([
            'user_id' => $user->id,
            'score' => $finalScore,
            'exam_date' => $endTime,
            'teka_tekis_id' => $answers[0]['teka_teki_id'], // Gunakan ID teka-teki pertama
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
