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
        // Ambil semua QuestionChoice beserta questions dan options-nya
        $questionChoices = QuestionChoice::with('questions.options')->get();
    
        // Siapkan array untuk response
        $response = [];
    
        // Loop melalui setiap QuestionChoice
        foreach ($questionChoices as $questionChoice) {
            $questionsArray = [];
    
            // Loop melalui setiap pertanyaan dari kuis
            foreach ($questionChoice->questions as $question) {
                $options = [];
    
                // Ambil semua opsi dari pertanyaan tersebut
                foreach ($question->options as $option) {
                    $options[] = $option->pilihan;
                }
    
                // Masukkan pertanyaan ke array tanpa ID
                $questionsArray[] = [
                    'pertanyaan' => $question->pertanyaan,
                    'jawaban' => $question->jawaban,
                    'pilihan' => $options
                ];
            }
    
            // Siapkan setiap kuis untuk dimasukkan ke response
            $response[] = [
                'id' => $questionChoice->id,
                'title' => $questionChoice->title,
                'questions' => $questionsArray
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
    public function store(Request $request)
    {
        // Validasi request seperti sebelumnya
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'soal' => 'required|array|min:1',
            'soal.*.pertanyaan' => 'required|string|max:255',
            'soal.*.jawaban' => 'required|string|max:255',
            'soal.*.pilihan' => 'required|array|min:4', // Minimal 4 pilihan jawaban
            'soal.*.pilihan.*' => 'required|string|max:255'
        ]);
    
        $response = []; // Array untuk menyimpan respons
    
        // Buat entri untuk QuestionChoice
        $questionChoice = QuestionChoice::create([
            'title' => $validatedData['title']
        ]);
    
        // Loop melalui soal
        foreach ($validatedData['soal'] as $soal) {
            $question = Question::create([
                'question_choice_id' => $questionChoice->id,
                'pertanyaan' => $soal['pertanyaan'],
                'jawaban' => $soal['jawaban'],
            ]);
    
            // Simpan setiap pilihan jawaban
            foreach ($soal['pilihan'] as $pilihan) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'pilihan' => $pilihan,
                ]);
            }
    
            // Masukkan pertanyaan ke array respons
            $response[] = [
                'pertanyaan' => $soal['pertanyaan'],
                'jawaban' => $soal['jawaban'],
                'pilihan' => $soal['pilihan']
            ];
        }
    
        // Kembalikan respons dalam bentuk array
        return response()->json([
            'id' => $questionChoice->id,
            'title' => $questionChoice->title,
            'soal' => $response
        ], 201);
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