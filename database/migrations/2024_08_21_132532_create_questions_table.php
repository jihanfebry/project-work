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
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Menggunakan UUID sebagai primary key
            $table->uuid('question_choice_id'); // Menggunakan UUID untuk foreign key
            $table->text('pertanyaan');  // Pertanyaan
            $table->string('jawaban');
            $table->timestamps();

            // Menambahkan foreign key constraint
            $table->foreign('question_choice_id')->references('id')->on('question_choices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
