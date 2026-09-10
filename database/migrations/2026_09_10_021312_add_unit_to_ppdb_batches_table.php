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
        Schema::table('ppdb_batches', function (Blueprint $table) {
            $table->enum('unit', ['MTRQ', 'TK', 'SD'])->default('MTRQ')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_batches', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }
};
