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
        Schema::table('detail_students_monitorings', function (Blueprint $table) {
            $table->dropForeign(['monitoring_id']);
            $table->foreign('monitoring_id')->references('id')->on('monitorings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_students_monitorings', function (Blueprint $table) {
            //
        });
    }
};
