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
        // Ambil semua data QuestionChoice beserta relasi ke QuestionOption
        $questions = QuestionChoice::with('options')->get();
    
        // Persiapkan response
        $response = [];
    
        foreach ($questions as $question) {
            $soalArray = [];
    
            // Ambil setiap pilihan dari pertanyaan
            foreach ($question->options as $option) {
                $soalArray[] = [
                    'pilihan' => $option->pilihan,
                ];
            }
    
            // Tambahkan ke response
            $response[] = [
                'id' => $question->id,
                'title' => $question->title,
                'pertanyaan' => $question->pertanyaan,
                'jawaban' => $question->jawaban,
                'pilihan' => $soalArray
            ];
        }
    
        // Kembalikan response dalam format JSON
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
        $response = [];
    
        foreach ($request->all() as $quizData) {
            // Buat QuestionChoice
            $questionChoice = QuestionChoice::create([
                'title' => $quizData['title']
            ]);
    
            $questionsArray = [];
    
            // Simpan setiap pertanyaan
            foreach ($quizData['soal'] as $soal) {
                // Buat entri untuk setiap pertanyaan
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
    
                // Masukkan pertanyaan ke array tanpa ID
                $questionsArray[] = [
                    'pertanyaan' => $soal['pertanyaan'],
                    'jawaban' => $soal['jawaban'],
                    'pilihan' => $soal['pilihan']
                ];
            }
    
            // Siapkan setiap kuis untuk dimasukkan ke response
            $response[] = [
                'id' => $questionChoice->id,
                'title' => $questionChoice->title,
                'questions' => $questionsArray
            ];
        }
    
        return response()->json($response, 201);
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
