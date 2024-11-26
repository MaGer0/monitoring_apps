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
        Schema::create('detail_students_monitorings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monitoring_id');
            $table->string('students_nisn');
            $table->boolean('absend');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('monitoring_id')->references('id')->on('monitorings');
            $table->foreign('students_nisn')->references('nisn')->on('students')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_students_monitorings');
    }
};
