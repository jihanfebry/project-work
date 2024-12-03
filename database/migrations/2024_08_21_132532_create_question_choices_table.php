<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('question_choices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_choice_id');
            $table->text('pertanyaan');  // Pertanyaan
            $table->string('jawaban');  
            $table->timestamps();

            // Foreign key untuk relasi ke question_choice_titles
            $table->foreign('question_choice_id')->references('id')->on('question_choice_titles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_choices');
    }
};