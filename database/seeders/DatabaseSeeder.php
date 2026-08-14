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
        // Insert Users (Admin, Musyrif, Wali)
        // Password default: 'password' (hash) -> $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
        \App\Models\User::insert([
            ['name' => 'Admin SUNTRI', 'email' => 'admin@suntri.id', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'role_id' => 1, 'phone' => '08100000001'],
            ['name' => 'Ust. Abdullah', 'email' => 'abdullah@suntri.id', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'role_id' => 2, 'phone' => '08100000002'],
            ['name' => 'Ust. Mansur', 'email' => 'mansur@suntri.id', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'role_id' => 2, 'phone' => '08100000003'],
            ['name' => 'Bpk. Ridwan', 'email' => 'ridwan@suntri.id', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'role_id' => 3, 'phone' => '08100000004'],
            ['name' => 'Mudir Pesantren', 'email' => 'mudir@suntri.id', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'role_id' => 6, 'phone' => '08100000006'],
        ]);

        // Insert Kelas
        \Illuminate\Support\Facades\DB::table('kelas')->insert([
            ['nama' => '7A', 'julukan' => 'Madinah', 'tingkat' => 7],
            ['nama' => '7B', 'julukan' => 'Makkah', 'tingkat' => 7],
            ['nama' => '8A', 'julukan' => 'Kairo', 'tingkat' => 8],
            ['nama' => '8B', 'julukan' => 'Baghdad', 'tingkat' => 8],
            ['nama' => '9A', 'julukan' => 'Istanbul', 'tingkat' => 9],
        ]);

        // Insert Halaqoh
        \Illuminate\Support\Facades\DB::table('halaqoh')->insert([
            ['nama' => 'Imam Malik', 'musyrif_id' => 2],
            ['nama' => "Imam Syafi'i", 'musyrif_id' => 2],
            ['nama' => 'Abu Bakr', 'musyrif_id' => 3],
            ['nama' => 'Utsman', 'musyrif_id' => 3],
            ['nama' => 'Umar', 'musyrif_id' => 3],
        ]);

        // Insert Jenis Tagihan
        \Illuminate\Support\Facades\DB::table('jenis_tagihan')->insert([
            ['nama' => 'SPP Bulanan', 'nominal' => 1500000, 'periode' => 'bulanan'],
            ['nama' => 'Uang Makan', 'nominal' => 850000, 'periode' => 'bulanan'],
            ['nama' => 'Uang Laundry', 'nominal' => 150000, 'periode' => 'bulanan'],
            ['nama' => 'Dana Gedung', 'nominal' => 5000000, 'periode' => 'sekali'],
        ]);

        // Call DummyDataSeeder
        $this->call(DummyDataSeeder::class);
    }
}
