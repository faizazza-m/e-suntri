<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tambah Santri
        $santri = [
            ['user_id' => null, 'nis' => '2024001', 'nama' => 'Ahmad Al-Fatih', 'jenis_kelamin' => 'L', 'kelas_id' => 1, 'halaqoh_id' => 1, 'tahun_masuk' => 2024],
            ['user_id' => null, 'nis' => '2024002', 'nama' => 'Zaid bin Haritsah', 'jenis_kelamin' => 'L', 'kelas_id' => 2, 'halaqoh_id' => 2, 'tahun_masuk' => 2024],
            ['user_id' => null, 'nis' => '2024003', 'nama' => 'Umar bin Khattab', 'jenis_kelamin' => 'L', 'kelas_id' => 3, 'halaqoh_id' => 3, 'tahun_masuk' => 2024],
        ];
        
        foreach($santri as $s) {
            \App\Models\Santri::create($s);
        }

        // 2. Tambah Hafalan
        \App\Models\HafalanSantri::create(['santri_id' => 1, 'juz_selesai' => 28]);
        \App\Models\HafalanSantri::create(['santri_id' => 2, 'juz_selesai' => 22]);
        \App\Models\HafalanSantri::create(['santri_id' => 3, 'juz_selesai' => 15]);

        // 3. Tambah Setoran
        \App\Models\Setoran::create(['santri_id' => 1, 'musyrif_id' => 2, 'tanggal' => now(), 'jenis' => 'hafalan_baru', 'surah' => 'Al-Mulk', 'ayat_dari' => 1, 'ayat_sampai' => 12, 'nilai' => 'Mumtaz']);
        \App\Models\Setoran::create(['santri_id' => 2, 'musyrif_id' => 2, 'tanggal' => now()->subMinutes(15), 'jenis' => 'murajaah', 'surah' => 'An-Naba', 'ayat_dari' => 1, 'ayat_sampai' => 40, 'nilai' => 'Jayyid Jiddan']);
        \App\Models\Setoran::create(['santri_id' => 3, 'musyrif_id' => 3, 'tanggal' => now()->subMinutes(45), 'jenis' => 'hafalan_baru', 'surah' => 'Ar-Rahman', 'ayat_dari' => 1, 'ayat_sampai' => 20, 'nilai' => 'Mumtaz']);
    }
}
