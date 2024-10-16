<?php

namespace App\Http\Controllers;

use App\Models\QuestionChoice;
use App\Models\QuestionOption;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionChoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questionChoices = QuestionChoice::with('questions.options')->get();

        $response = [];

        foreach ($questionChoices as $questionChoice) {
            $questionsArray = [];

            foreach ($questionChoice->questions as $question) {
                $options = [];

                foreach ($question->options as $option) {
                    $options[] = $option->pilihan;
                }

                $questionsArray[] = [
                    'pertanyaan' => $question->pertanyaan,
                    'jawaban' => $question->jawaban,
                    'pilihan' => $options
                ];
            }

            $response[] = [
                'id' => $questionChoice->id, // UUID
                'title' => $questionChoice->title,
                'questions' => $questionsArray
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'soal' => 'required|array|min:1',
            'soal.*.pertanyaan' => 'required|string|max:255',
            'soal.*.jawaban' => 'required|string|max:255',
            'soal.*.pilihan' => 'required|array|min:4',
            'soal.*.pilihan.*' => 'required|string|max:255'
        ]);

        $response = [];

        $questionChoice = QuestionChoice::create([
            'title' => $validatedData['title']
        ]);

        foreach ($validatedData['soal'] as $soal) {
            $question = Question::create([
                'question_choice_id' => $questionChoice->id,
                'pertanyaan' => $soal['pertanyaan'],
                'jawaban' => $soal['jawaban'],
            ]);

            foreach ($soal['pilihan'] as $pilihan) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'pilihan' => $pilihan,
                ]);
            }

            $response[] = [
                'pertanyaan' => $soal['pertanyaan'],
                'jawaban' => $soal['jawaban'],
                'pilihan' => $soal['pilihan']
            ];
        }

        return response()->json([
            'id' => $questionChoice->id,
            'title' => $questionChoice->title,
            'soal' => $response
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $questionChoice = QuestionChoice::with('questions.options')->findOrFail($id);

        $questionsArray = [];

        foreach ($questionChoice->questions as $question) {
            $options = [];

            foreach ($question->options as $option) {
                $options[] = $option->pilihan;
            }

            $questionsArray[] = [
                'pertanyaan' => $question->pertanyaan,
                'jawaban' => $question->jawaban,
                'pilihan' => $options
            ];
        }

        return response()->json([
            'id' => $questionChoice->id,
            'title' => $questionChoice->title,
            'questions' => $questionsArray
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestionChoice $questionChoice)
    {
        $validatedData = $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string|max:255',
            'pilihan' => 'required|array|min:4',
            'pilihan.*' => 'required|string|max:255'
        ]);

        $question = Question::where('question_choice_id', $questionChoice->id)
            ->where('pertanyaan', $validatedData['pertanyaan'])
            ->first();

        if (!$question) {
            return response()->json(['error' => 'Question not found'], 404);
        }

        $question->update([
            'jawaban' => $validatedData['jawaban']
        ]);

        QuestionOption::where('question_id', $question->id)->delete();

        foreach ($validatedData['pilihan'] as $pilihan) {
            QuestionOption::create([
                'question_id' => $question->id,
                'pilihan' => $pilihan
            ]);
        }

        return response()->json([
            'message' => 'Question updated successfully',
            'pertanyaan' => $question->pertanyaan,
            'jawaban' => $question->jawaban,
            'pilihan' => $validatedData['pilihan']
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionChoice $questionChoice)
    {
        foreach ($questionChoice->questions as $question) {
            QuestionOption::where('question_id', $question->id)->delete();
            $question->delete();
        }

        $questionChoice->delete();

        return response()->json(['message' => 'QuestionChoice deleted successfully'], 200);
    }
}
