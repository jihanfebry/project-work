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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('birth_date')->nullable();
            $table->enum('gender', ['man', 'woman'])->nullable();
            $table->string('class')->nullable();
            $table->string('parents')->nullable();
            $table->string('phone_number')->nullable();          
            $table->string('email')->unique()->nullable();
            $table->string('addres')->nullable();
            $table->enum('role', ['guru', 'murid'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
