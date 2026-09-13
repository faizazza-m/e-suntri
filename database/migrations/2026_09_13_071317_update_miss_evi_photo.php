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
        DB::table('users')->where('name', 'Miss Evi')->update(['foto' => 'images/profiles/missevi.jpg']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('name', 'Miss Evi')->update(['foto' => null]);
    }
};
