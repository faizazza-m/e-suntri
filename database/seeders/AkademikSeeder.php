<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Santri;
use App\Models\NilaiAkademik;
use Illuminate\Support\Facades\DB;

class AkademikSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada guru
        $guru = User::where('role_id', 2)->first() ?? User::find(1);

        // 1. Mata Pelajaran
        $mapels = [
            ['nama' => 'Tauhid & Aqidah', 'kode' => 'AQD-101'],
            ['nama' => 'Fiqih Ibadah', 'kode' => 'FQH-101'],
            ['nama' => 'Bahasa Arab', 'kode' => 'ARB-101'],
            ['nama' => 'Sirah Nabawiyah', 'kode' => 'SRH-101'],
            ['nama' => 'Tahsin & Tajwid', 'kode' => 'TJW-101'],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MataPelajaran::truncate();
        JadwalPelajaran::truncate();
        NilaiAkademik::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($mapels as $mapel) {
            MataPelajaran::create(array_merge($mapel, ['guru_id' => $guru->id]));
        }

        // 2. Jadwal Pelajaran
        $kelas = Kelas::first();
        if (!$kelas) {
            $kelas = Kelas::create([
                'nama' => 'Kelas VII - A',
                'tingkat' => 'Tsanawiyah',
                'wali_kelas' => $guru->id,
                'kapasitas' => 30
            ]);
        }

        $semuaMapel = MataPelajaran::all();
        $hariArr = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jamArr = [
            ['mulai' => '07:30:00', 'selesai' => '09:00:00'],
            ['mulai' => '09:30:00', 'selesai' => '11:00:00'],
            ['mulai' => '13:00:00', 'selesai' => '14:30:00'],
        ];

        foreach ($hariArr as $hari) {
            foreach ($jamArr as $jam) {
                JadwalPelajaran::create([
                    'kelas_id' => $kelas->id,
                    'mapel_id' => $semuaMapel->random()->id,
                    'hari' => $hari,
                    'jam_mulai' => $jam['mulai'],
                    'jam_selesai' => $jam['selesai'],
                    'ruang' => 'Ruang ' . rand(1, 5)
                ]);
            }
        }

        // 3. Nilai Akademik
        $santris = Santri::take(5)->get();
        if ($santris->count() > 0) {
            foreach ($santris as $santri) {
                foreach ($semuaMapel as $mapel) {
                    $uts = rand(70, 95);
                    $uas = rand(75, 98);
                    $harian = rand(80, 95);
                    $akhir = ($harian * 0.3) + ($uts * 0.3) + ($uas * 0.4);
                    
                    $predikat = 'E';
                    if ($akhir >= 90) $predikat = 'A';
                    elseif ($akhir >= 80) $predikat = 'B';
                    elseif ($akhir >= 70) $predikat = 'C';
                    elseif ($akhir >= 60) $predikat = 'D';

                    NilaiAkademik::create([
                        'santri_id' => $santri->id,
                        'mapel_id' => $mapel->id,
                        'semester' => 1,
                        'tahun_ajaran' => '2026/2027',
                        'nilai_harian' => $harian,
                        'nilai_uts' => $uts,
                        'nilai_uas' => $uas,
                        'nilai_akhir' => $akhir,
                        'predikat' => $predikat
                    ]);
                }
            }
        }
    }
}
