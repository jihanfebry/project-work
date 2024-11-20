<?php

namespace App\Http\Controllers;

use App\Models\QuestionChoiceTitle;
use App\Models\QuestionOption;
use App\Models\QuestionChoice;
use Illuminate\Http\Request;

class QuestionChoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua QuestionChoice beserta questions dan options-nya
        $questionChoices = QuestionChoiceTitle::with('questions.options')->get();
    
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
        $questionChoice = QuestionChoiceTitle::create([
            'title' => $validatedData['title']
        ]);
    
        // Loop melalui soal
        foreach ($validatedData['soal'] as $soal) {
            $question = QuestionChoice::create([
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

    // Kembalikan respons dalam bentuk array
  

    /**
 * Display the specified resource.
 */
        public function show($id)
{
    // Cari QuestionChoice berdasarkan id yang diberikan dan muat relationships
    $questionChoice = QuestionChoiceTitle::with('questions.options')->findOrFail($id);

    // Siapkan array untuk response
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

    // Return response dalam bentuk JSON
    return response()->json([
        'id' => $questionChoice->id,
        'title' => $questionChoice->title,
        'questions' => $questionsArray
    ], 200);
}


        /**
         * Show the form for editing the specified resource.
         */
        public function edit(QuestionChoiceTitle $questionChoice)
        {
            // Jika Anda menggunakan form untuk mengedit di frontend, return data dari QuestionChoice
            return response()->json($questionChoice, 200);
        }

        /**
         * Update the specified resource in storage.
         */
        /**
 * Update the specified resource in storage.
 */
public function update(Request $request, $questionChoiceId, $questionId)
{
    // Validasi request untuk pertanyaan, jawaban, dan pilihan
    $validatedData = $request->validate([
        'pertanyaan' => 'required|string|max:255', // Pertanyaan yang di-update
        'jawaban' => 'required|string|max:255',    // Jawaban yang di-update
        'pilihan' => 'required|array|min:4',       // Pilihan baru yang akan di-update
        'pilihan.*' => 'required|string|max:255'   // Setiap pilihan harus berupa string
    ]);

    // Cari pertanyaan berdasarkan `id` dan `question_choice_id`
    $question = QuestionChoice::where('id', $questionId)
        ->where('question_choice_id', $questionChoiceId)
        ->first();

    if (!$question) {
        // Jika pertanyaan tidak ditemukan, kembalikan response error
        return response()->json(['error' => 'Question not found'], 404);
    }

    // Update pertanyaan dan jawaban yang ditemukan
    $question->update([
        'pertanyaan' => $validatedData['pertanyaan'],
        'jawaban' => $validatedData['jawaban']
    ]);

    // Hapus pilihan lama dan tambahkan pilihan baru
    QuestionOption::where('question_id', $question->id)->delete();

    foreach ($validatedData['pilihan'] as $pilihan) {
        QuestionOption::create([
            'question_id' => $question->id,
            'pilihan' => $pilihan
        ]);
    }

    // Kembalikan response sukses
    return response()->json([
        'message' => 'Question updated successfully',
        'pertanyaan' => $question->pertanyaan,
        'jawaban' => $question->jawaban,
        'pilihan' => $validatedData['pilihan']
    ], 200);
}

public function destroy($questionChoiceId, $questionId)
{
    // Cari pertanyaan berdasarkan `id` dan `question_choice_id`
    $question = QuestionChoice::where('id', $questionId)
        ->where('question_choice_id', $questionChoiceId)
        ->first();

    if (!$question) {
        // Jika pertanyaan tidak ditemukan, kembalikan response error
        return response()->json(['error' => 'Question not found'], 404);
    }

    // Hapus semua pilihan yang terkait dengan pertanyaan
    QuestionOption::where('question_id', $question->id)->delete();
    
    // Hapus pertanyaan
    $question->delete();

    // Kembalikan response sukses
    return response()->json(['message' => 'Question deleted successfully'], 200);
}

public function deleteAll($questionChoiceId)
{
    // Gunakan $questionChoiceId alih-alih $id
    $questionChoice = QuestionChoiceTitle::find($questionChoiceId);

    if (!$questionChoice) {
        return response()->json([
            'message' => 'QuestionChoiceTitle not found'
        ], 404);
    }

    // Operasikan dengan $questionChoiceId
    $questionChoices = QuestionChoice::where('question_choice_id', $questionChoiceId)->get();

    foreach ($questionChoices as $question) {
        QuestionOption::where('question_id', $question->id)->delete();
        $question->delete();
    }

    $questionChoice->delete();

    return response()->json([
        'message' => 'QuestionChoiceTitle and related data deleted successfully'
    ], 200);
}



}