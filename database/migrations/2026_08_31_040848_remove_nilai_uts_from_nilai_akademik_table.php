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
        Schema::table('nilai_akademik', function (Blueprint $table) {
            $table->dropColumn('nilai_uts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_akademik', function (Blueprint $table) {
            $table->decimal('nilai_uts', 5, 2)->nullable()->after('nilai_harian');
        });
    }
};
