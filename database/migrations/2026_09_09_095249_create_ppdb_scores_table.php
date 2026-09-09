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
        Schema::create('ppdb_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('ppdb_students')->cascadeOnDelete();
            $table->integer('quran_test')->nullable();
            $table->integer('written_test')->nullable();
            $table->integer('interview_test')->nullable();
            $table->integer('total_score')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_scores');
    }
};
