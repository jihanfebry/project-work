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
        Schema::create('teka_tekis', function (Blueprint $table) {
            $table->id();
            $table->string('gambar'); // URL atau path ke gambar
            $table->string('jawaban'); // Jawaban yang benar
            $table->string('clue'); // Petunjuk atau clue
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teka_tekis');
    }
};
