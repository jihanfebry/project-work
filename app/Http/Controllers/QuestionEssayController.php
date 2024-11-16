<?php

namespace App\Http\Controllers;

use App\Models\QuestionEssay;
use App\Models\EssayTitle;
use Illuminate\Http\Request;

class QuestionEssayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua essay beserta pertanyaan terkait
        $essays = EssayTitle::with('questions')->get();

        // Format respons JSON sesuai struktur yang diinginkan
        $formattedResponse = $essays->map(function ($essay) {
            return [
                'id' => $essay->id,
                'title' => $essay->title,
                'questions' => $essay->questions->map(function ($question) {
                    return [
                        'pertanyaan' => $question->pertanyaan,
                        'jawaban' => $question->jawaban,
                    ];
                }),
            ];
        });

        return response()->json($formattedResponse);
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
        // Validasi data
        $request->validate([
            'title' => 'required|string',
            'questions' => 'required|array',
            'questions.*.pertanyaan' => 'required|string',
            'questions.*.jawaban' => 'required|string',
        ]);

        // Membuat Essay baru
        $essay = EssayTitle::create([
            'title' => $request->title,
        ]);

        // Menyimpan setiap pertanyaan yang terkait dengan Essay
        foreach ($request->questions as $question) {
            $essay->questions()->create([
                'pertanyaan' => $question['pertanyaan'],
                'jawaban' => $question['jawaban'],
            ]);
        }

        // Menyiapkan respons sesuai permintaan
        return response()->json([
            'id' => $essay->id,
            'title' => $essay->title,
            'soal' => $essay->questions->map(function ($question) {
                return [
                    'pertanyaan' => $question->pertanyaan,
                    'jawaban' => $question->jawaban,
                ];
            }),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(QuestionEssay $questionEssay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestionEssay $questionEssay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestionEssay $questionEssay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionEssay $questionEssay)
    {
        //
    }
}
