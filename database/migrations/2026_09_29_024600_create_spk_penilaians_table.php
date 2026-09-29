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
        Schema::create('spk_penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->float('c1_nilai')->nullable();
            $table->float('c2_nilai')->nullable();
            $table->float('c3_nilai')->nullable();
            $table->float('c4_nilai')->nullable(); // Pelanggaran
            $table->string('periode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_penilaians');
    }
};
