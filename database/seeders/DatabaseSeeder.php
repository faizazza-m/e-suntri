<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Eksekusi data asli dari suntri_postgres.sql
        $sqlPath = database_path('suntri_postgres.sql');
        if (file_exists($sqlPath)) {
            // Disable foreign key checks for Postgres
            \Illuminate\Support\Facades\DB::statement("SET session_replication_role = 'replica';");
            
            \Illuminate\Support\Facades\DB::unprepared(file_get_contents($sqlPath));
            
            // Re-enable foreign key checks
            \Illuminate\Support\Facades\DB::statement("SET session_replication_role = 'origin';");
        }

        // 2. Update PostgreSQL sequences
        $tables = [
            'hafalan_santri', 'halaqoh', 'jadwal_pelajaran', 'jenis_tagihan', 
            'kelas', 'mata_pelajaran', 'nilai_akademik', 'roles', 'santri', 
            'setoran', 'users', 'wali_santri'
        ];
        foreach ($tables as $table) {
            \Illuminate\Support\Facades\DB::statement("SELECT setval(pg_get_serial_sequence('\"{$table}\"', 'id'), coalesce(max(id), 0) + 1, false) FROM \"{$table}\";");
        }
    }
}
