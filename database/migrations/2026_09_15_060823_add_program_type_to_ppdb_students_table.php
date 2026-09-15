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
        Schema::table('ppdb_students', function (Blueprint $table) {
            $table->enum('program_type', ['mondok', 'pulang_pergi'])->default('mondok')->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_students', function (Blueprint $table) {
            $table->dropColumn('program_type');
        });
    }
};
