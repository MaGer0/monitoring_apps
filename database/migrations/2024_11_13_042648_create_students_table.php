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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('teachers_nik');
            $table->string('nisn');
            $table->string('name', 100);
            $table->string('class');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['teachers_nik', 'nisn'], 'student_unique_upsert');
            $table->unique('nisn', 'student_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
