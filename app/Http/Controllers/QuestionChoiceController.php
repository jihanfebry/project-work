<?php

namespace App\Http\Controllers;

use App\Models\QuestionChoice;
use App\Models\QuestionOption;
use Illuminate\Http\Request;

class QuestionChoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        foreach ($request->soal as $soal) {
            $questionChoice = QuestionChoice::create([
                'title' => $request->title,
                'pertanyaan' => $soal['pertanyaan'],
                'jawaban' => $soal['jawaban'],
            ]);

            foreach ($soal['pilihan'] as $pilihan) {
                QuestionOption::create([
                    'question_choice_id' => $questionChoice->id,
                    'pilihan' => $pilihan,
                ]);
            }
        }

        return response()->json(['message' => 'Quiz created successfully'], 201);
    }

    public function show($id)
    {
        $questionChoice = QuestionChoice::with('options')->findOrFail($id);
        return response()->json($questionChoice);
    }

    public function checkAnswer(Request $request, $questionId)
    {
    $questionChoice = QuestionChoice::with('options')->findOrFail($questionId);
    $userAnswer = $request->jawaban;

    $isCorrect = $questionChoice->jawaban === $userAnswer;

    return response()->json([
        'title' => $questionChoice->title,
        'question' => $questionChoice->pertanyaan,
        'user_answer' => $userAnswer,
        'correct_answer' => $isCorrect ? null : $questionChoice->jawaban,
        'is_correct' => $isCorrect
    ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestionChoice $questionChoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestionChoice $questionChoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionChoice $questionChoice)
    {
        //
    }
}
