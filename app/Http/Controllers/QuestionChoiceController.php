<?php

namespace App\Http\Controllers;

use App\Models\QuestionChoice;
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
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        $questionChoice = QuestionChoice::create([
            'question_text' => $request->question_text,
        ]);

        foreach ($request->options as $option) {
            $questionChoice->options()->create($option);
        }

        return response()->json(['message' => 'Question and options saved successfully.'], 201);
    }

    public function checkAnswer(Request $request, $id)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer|exists:question_options,id',
        ]);

        $questionChoice = QuestionChoice::findOrFail($id);
        $correctAnswers = $questionChoice->options()->where('is_correct', true)->pluck('id')->toArray();

        if (array_diff($correctAnswers, $request->answers) || array_diff($request->answers, $correctAnswers)) {
            return response()->json(['message' => 'Incorrect answers.'], 400);
        }

        return response()->json(['message' => 'Correct answers!'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(QuestionChoice $questionChoice)
    {
        //
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
