<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE pengumuman DROP CONSTRAINT IF EXISTS pengumuman_target_check");
            DB::statement("ALTER TABLE pengumuman ADD CONSTRAINT pengumuman_target_check CHECK (target::text = ANY (ARRAY['semua'::character varying, 'wali'::character varying, 'santri'::character varying, 'musyrif'::character varying, 'guru'::character varying]::text[]))");
        } else {
            DB::statement("ALTER TABLE pengumuman MODIFY target ENUM('semua', 'wali', 'santri', 'musyrif', 'guru') DEFAULT 'semua'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE pengumuman DROP CONSTRAINT IF EXISTS pengumuman_target_check");
            // Revert back to original options
            // Note: This could fail if there are existing rows with 'guru'
            DB::statement("ALTER TABLE pengumuman ADD CONSTRAINT pengumuman_target_check CHECK (target::text = ANY (ARRAY['semua'::character varying, 'wali'::character varying, 'santri'::character varying, 'musyrif'::character varying]::text[]))");
        } else {
            DB::statement("ALTER TABLE pengumuman MODIFY target ENUM('semua', 'wali', 'santri', 'musyrif') DEFAULT 'semua'");
        }
    }
};
