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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Hanya satu deklarasi untuk user_id
            $table->string('name');
            $table->string('birth_date')->nullable();
            $table->enum('gender', ['laki-laki', 'perempuan'])->nullable();
            // $table->string('class')->nullable();
            $table->string('parent')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('addres')->nullable();
            // $table->foreign('kelas_id')->references('id')->on('kelas'); // Pastikan kelas_id didefinisikan sebelumnya
            $table->timestamps();
            
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('cascade');
            // Definisikan foreign key untuk user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};

