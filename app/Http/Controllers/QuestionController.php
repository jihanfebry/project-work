<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
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

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'question_text' => 'required|string',
                'choices' => 'required|array',
                'choices.*.answer_text' => 'required|string',
                'choices.*.is_correct' => 'required|boolean',
            ]);
    
            $question = Question::create([
                'question_text' => $request->question_text,
            ]);
    
            foreach ($request->choices as $choice) {
                $question->choices()->create($choice);
            }
    
            return response()->json(['message' => 'Question and choices saved successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function checkAnswer(Request $request, $id)
    {
        try {
            $request->validate([
                'answers' => 'required|array',
                'answers.*' => 'required|integer|exists:question_choices,id',
            ]);
    
            $question = Question::findOrFail($id);
            $correctAnswers = $question->choices()->where('is_correct', true)->pluck('id')->toArray();
    
            if (array_diff($correctAnswers, $request->answers) || array_diff($request->answers, $correctAnswers)) {
                return response()->json(['message' => 'Incorrect answers.'], 400);
            }
    
            return response()->json(['message' => 'Correct answers!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    

    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        //
    }
}
