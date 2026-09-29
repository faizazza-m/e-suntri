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
        Schema::create('spk_kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // C1, C2, C3, C4, C5
            $table->string('nama');
            $table->float('bobot'); // Persentase (e.g. 20, 25)
            $table->enum('sifat', ['benefit', 'cost']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_kriterias');
    }
};
