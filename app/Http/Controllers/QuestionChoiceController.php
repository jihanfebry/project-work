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
        // Ambil semua QuestionChoice beserta pilihan opsinya
    $questionChoices = QuestionChoice::with('options')->get();

    // Siapkan array untuk response
    $response = [];

    // Loop melalui setiap QuestionChoice
    foreach ($questionChoices as $questionChoice) {
        // Siapkan array pilihan (opsi jawaban)
        $options = [];
        foreach ($questionChoice->options as $option) {
            $options[] = $option->pilihan;  // Mengambil pilihan dari QuestionOption
        }

        // Siapkan setiap soal untuk dimasukkan ke response
        $response[] = [
            'id' => $questionChoice->id,
            'title' => $questionChoice->title,              // Judul kuis
            'pertanyaan' => $questionChoice->pertanyaan,    // Pertanyaan
            'jawaban' => $questionChoice->jawaban,          // Jawaban benar
            'pilihan' => $options                           // Pilihan jawaban
        ];
    }

    // Return response dalam bentuk JSON
    return response()->json($response, 200);
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
    // public function store(Request $request)
    // {
    //     foreach ($request->soal as $soal) {
    //         $questionChoice = QuestionChoice::create([
    //             'title' => $request->title,
    //             'pertanyaan' => $soal['pertanyaan'],
    //             'jawaban' => $soal['jawaban'],
    //         ]);

    //         foreach ($soal['pilihan'] as $pilihan) {
    //             QuestionOption::create([
    //                 'question_choice_id' => $questionChoice->id,
    //                 'pilihan' => $pilihan,
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Quiz created successfully'], 201);
    // }

    public function store(Request $request)
{
    $response = [];

    foreach ($request->all() as $quizData) {
        // Siapkan array untuk menyimpan soal
        $soalArray = [];

        foreach ($quizData['soal'] as $soal) {
            // Buat entri untuk QuestionChoice (pertanyaan dan jawaban)
            $questionChoice = QuestionChoice::create([
                'title' => $quizData['title'],           // Menyimpan judul kuis
                'pertanyaan' => $soal['pertanyaan'],     // Menyimpan pertanyaan
                'jawaban' => $soal['jawaban'],           // Menyimpan jawaban benar
            ]);

            // Buat entri untuk setiap pilihan (opsi jawaban)
            foreach ($soal['pilihan'] as $pilihan) {
                QuestionOption::create([
                    'question_choice_id' => $questionChoice->id,
                    'pilihan' => $pilihan,               // Menyimpan pilihan jawaban
                ]);
            }

            // Siapkan soal untuk response
            $soalArray[] = [
                'pertanyaan' => $soal['pertanyaan'],
                'pilihan' => $soal['pilihan'],
                'jawaban' => $soal['jawaban']
            ];
        }

        // Siapkan quiz untuk response
        $response[] = [
            'id' => $questionChoice->id,
            'title' => $quizData['title'],
            'soal' => $soalArray
        ];
    }

    return response()->json($response, 201);
}


public function show($id)
{
    
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
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionChoice $questionChoice)
    {
        

    }
}
