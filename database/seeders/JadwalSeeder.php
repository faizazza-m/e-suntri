<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Hash;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $ustadzRole = Role::where('name', 'ustadz')->first();
        $roleId = $ustadzRole ? $ustadzRole->id : 5;
        
        $teachers = [
            'Miss Evi' => 'Bahasa Inggris',
            'Ustadz Faiz' => ['IPTEK', 'Matematika'],
            'Ustadz Sam Sam, Lc' => 'Fiqih',
            'Ustadz Adnan Lc' => 'Hadist',
            'Ustadz Yaman' => ['Nahwu', 'Bahasa Arab'],
            'Ust Dahlan, Lc' => 'Adab & Akhlak',
            'Ust Andi, Lc' => 'Aqidah',
            'Ust Dr Ilham Waliyudin, M.A, M.Pd' => ['Tajwid', 'Matan']
        ];

        $teacherIds = [];
        $mapelIds = [];

        foreach ($teachers as $name => $subjects) {
            $user = User::firstOrCreate(
                ['name' => $name],
                [
                    'email' => strtolower(str_replace([' ', ',', '.'], '', $name)) . '@example.com',
                    'password' => Hash::make('password'),
                    'role_id' => $roleId
                ]
            );
            $teacherIds[$name] = $user->id;

            if (!is_array($subjects)) {
                $subjects = [$subjects];
            }

            foreach ($subjects as $subject) {
                $mapel = MataPelajaran::firstOrCreate(
                    ['nama' => $subject, 'guru_id' => $user->id],
                    ['kode' => strtoupper(substr($subject, 0, 3)) . '-' . rand(100, 999)]
                );
                $mapelIds[$subject] = $mapel->id;
            }
        }

        $kelasAwwal = Kelas::where('nama', 'Mustawa Awwal')->first();
        $kelasTsani = Kelas::where('nama', 'Mustawa Tsani')->first();

        $jadwalAwwal = [
            ['hari' => 'Senin', 'jam_mulai' => '07:15', 'jam_selesai' => '08:15', 'mapel' => 'Bahasa Inggris'],
            ['hari' => 'Senin', 'jam_mulai' => '08:15', 'jam_selesai' => '09:15', 'mapel' => 'IPTEK'],
            ['hari' => 'Selasa', 'jam_mulai' => '07:15', 'jam_selesai' => '08:15', 'mapel' => 'Fiqih'],
            ['hari' => 'Selasa', 'jam_mulai' => '08:15', 'jam_selesai' => '09:15', 'mapel' => 'Hadist'],
            ['hari' => 'Rabu', 'jam_mulai' => '07:15', 'jam_selesai' => '08:15', 'mapel' => 'Matematika'],
            ['hari' => 'Rabu', 'jam_mulai' => '08:15', 'jam_selesai' => '09:15', 'mapel' => 'Nahwu'],
            ['hari' => 'Kamis', 'jam_mulai' => '07:15', 'jam_selesai' => '08:15', 'mapel' => 'Bahasa Arab'],
            ['hari' => 'Kamis', 'jam_mulai' => '08:15', 'jam_selesai' => '09:15', 'mapel' => 'Adab & Akhlak'],
            ['hari' => 'Jumat', 'jam_mulai' => '07:15', 'jam_selesai' => '08:15', 'mapel' => 'Aqidah'],
            ['hari' => 'Jumat', 'jam_mulai' => '08:15', 'jam_selesai' => '09:15', 'mapel' => 'Tajwid'],
            ['hari' => 'Sabtu', 'jam_mulai' => '07:15', 'jam_selesai' => '08:15', 'mapel' => 'Matan'],
        ];

        $jadwalTsani = [
            ['hari' => 'Senin', 'jam_mulai' => '07:15', 'jam_selesai' => '08:15', 'mapel' => 'IPTEK'],
            ['hari' => 'Senin', 'jam_mulai' => '08:15', 'jam_selesai' => '09:15', 'mapel' => 'Bahasa Inggris'],
            ['hari' => 'Selasa', 'jam_mulai' => '07:15', 'jam_selesai' => '08:15', 'mapel' => 'Hadist'],
            ['hari' => 'Selasa', 'jam_mulai' => '08:15', 'jam_selesai' => '09:15', 'mapel' => 'Fiqih'],
            ['hari' => 'Rabu', 'jam_mulai' => '07:15', 'jam_selesai' => '08:15', 'mapel' => 'Nahwu'],
            ['hari' => 'Rabu', 'jam_mulai' => '08:15', 'jam_selesai' => '09:15', 'mapel' => 'Matematika'],
            ['hari' => 'Kamis', 'jam_mulai' => '07:15', 'jam_selesai' => '08:15', 'mapel' => 'Adab & Akhlak'],
            ['hari' => 'Kamis', 'jam_mulai' => '08:15', 'jam_selesai' => '09:15', 'mapel' => 'Bahasa Arab'],
            ['hari' => 'Jumat', 'jam_mulai' => '07:15', 'jam_selesai' => '08:15', 'mapel' => 'Tajwid'],
            ['hari' => 'Jumat', 'jam_mulai' => '08:15', 'jam_selesai' => '09:15', 'mapel' => 'Aqidah'],
            ['hari' => 'Sabtu', 'jam_mulai' => '08:15', 'jam_selesai' => '09:15', 'mapel' => 'Matan'],
        ];

        if ($kelasAwwal) {
            foreach ($jadwalAwwal as $j) {
                JadwalPelajaran::firstOrCreate([
                    'kelas_id' => $kelasAwwal->id,
                    'hari' => $j['hari'],
                    'jam_mulai' => $j['jam_mulai'],
                ], [
                    'jam_selesai' => $j['jam_selesai'],
                    'mapel_id' => $mapelIds[$j['mapel']],
                    'ruang' => 'Ruang 1'
                ]);
            }
        }

        if ($kelasTsani) {
            foreach ($jadwalTsani as $j) {
                JadwalPelajaran::firstOrCreate([
                    'kelas_id' => $kelasTsani->id,
                    'hari' => $j['hari'],
                    'jam_mulai' => $j['jam_mulai'],
                ], [
                    'jam_selesai' => $j['jam_selesai'],
                    'mapel_id' => $mapelIds[$j['mapel']],
                    'ruang' => 'Ruang 2'
                ]);
            }
        }
    }
}
