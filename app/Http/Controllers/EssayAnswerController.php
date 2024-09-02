<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EssayAnswer;
use App\Models\QuestionEssay;

class EssayAnswerController extends Controller
{
    // Menambahkan essay baru dengan gambar soal
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question_id' => 'required|exists:question_essays,id',
            'answer_text' => 'required|string',
            'is_correct' => 'boolean',
        ]);

        // Ambil question_image dari QuestionEssay terkait
        $questionEssay = QuestionEssay::findOrFail($validatedData['question_id']);
        $validatedData['question_image_id'] = $questionEssay->id; // Simpan ID dari QuestionEssay

        // Simpan essay answer baru
        $essayAnswer = EssayAnswer::create($validatedData);

        return response()->json([
            'message' => 'Essay answer created successfully',
            'data' => $essayAnswer,
        ], 201);
    }

    // Memperbarui essay answer yang ada dengan gambar soal
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'answer_text' => 'string',
            'is_correct' => 'boolean',
            'question_id' => 'exists:question_essays,id',
        ]);

        $essayAnswer = EssayAnswer::findOrFail($id);

        // Jika question_id diubah, update juga question_image_id
        if (isset($validatedData['question_id'])) {
            $questionEssay = QuestionEssay::findOrFail($validatedData['question_id']);
            $validatedData['question_image_id'] = $questionEssay->id; // Update ID dari QuestionEssay
        }

        $essayAnswer->update($validatedData);

        return response()->json([
            'message' => 'Essay answer updated successfully',
            'data' => $essayAnswer,
        ], 200);
    }

    // Menampilkan semua essay answers berdasarkan question_id
    public function showByQuestion($question_id)
    {
        $essayAnswers = EssayAnswer::where('question_id', $question_id)->get();

        return response()->json([
            'data' => $essayAnswers,
        ], 200);
    }
}
