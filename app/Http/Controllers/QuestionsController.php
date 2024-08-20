<?php

namespace App\Http\Controllers;

use App\Models\Questions;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    
    public function submitAnswer(Request $request)
    {
        $questionId = $request->input('question_id');
        $selectedChoiceId = $request->input('choice_id');

        // Dapatkan pilihan yang dipilih siswa
        $selectedChoice = Choice::where('id', $selectedChoiceId)
                                ->where('question_id', $questionId)
                                ->first();

        if ($selectedChoice) {
            if ($selectedChoice->true_answer) {
                return response()->json(['message' => 'Correct answer!'], 200);
            } else {
                return response()->json(['message' => 'Wrong answer!'], 200);
            }
        } else {
            return response()->json(['message' => 'Invalid choice!'], 400);
        }
}


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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Questions $questions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Questions $questions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Questions $questions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Questions $questions)
    {
        //
    }
}
