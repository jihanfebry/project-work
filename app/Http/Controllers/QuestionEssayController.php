<?php

namespace App\Http\Controllers;

use App\Models\QuestionEssay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionEssayController extends Controller
{
    public function index()
    {
        $questions = QuestionEssay::all();
        return response()->json($questions);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $question = new QuestionEssay;

        if ($request->hasFile('question_image')) {
            $images = [];
            foreach ($request->file('question_image') as $image) {
                $path = $image->store('question_images', 'public');
                $images[] = $path;
            }
            $question->question_image = json_encode($images); // Store as JSON array
        }

        $question->save();

        return response()->json(['message' => 'Question created successfully', 'question' => $question], 201);
    }

    public function show(QuestionEssay $questionEssay)
    {
        $questionEssay->question_image = json_decode($questionEssay->question_image);
        return response()->json($questionEssay);
    }

    public function update(Request $request, QuestionEssay $questionEssay)
    {
        $validatedData = $request->validate([
            'question_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('question_image')) {
            // Delete old images
            $oldImages = json_decode($questionEssay->question_image);
            if ($oldImages) {
                foreach ($oldImages as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            // Store new images
            $images = [];
            foreach ($request->file('question_image') as $image) {
                $path = $image->store('question_images', 'public');
                $images[] = $path;
            }
            $questionEssay->question_image = json_encode($images);
        }

        $questionEssay->save();

        return response()->json(['message' => 'Question updated successfully', 'question' => $questionEssay]);
    }

    public function destroy(QuestionEssay $questionEssay)
    {
        $images = json_decode($questionEssay->question_image);
        if ($images) {
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $questionEssay->delete();

        return response()->json(['message' => 'Question deleted successfully']);
    }
}
