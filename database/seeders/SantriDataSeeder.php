<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Santri;
use App\Models\Kelas;

class SantriDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Abdurrahman Al-Bakasy', 'kelas' => '9 SMP', 'nis' => '3118507037'],
            ['nama' => 'Alkhalifi Zavier Mikhail', 'kelas' => '8 SMP', 'nis' => null],
            ['nama' => 'Arkaan Musliim Icti', 'kelas' => '9 SMP', 'nis' => '3115031717'],
            ['nama' => 'Dafa Bunyanudin', 'kelas' => '9 SMP', 'nis' => '0118538101'],
            ['nama' => 'Hudzaifah Adzka Hidayat', 'kelas' => '9 SMP', 'nis' => '3122708812'],
            ['nama' => 'M. Adhyasta Abd Jabbar', 'kelas' => '9 SMP', 'nis' => '0123452346'],
            ['nama' => 'Muhammad Azam', 'kelas' => '9 SMP', 'nis' => '0117598383'],
            ['nama' => 'Muhammad Azzam Alkhaf Akbar', 'kelas' => '8 SMP', 'nis' => '3121410245'],
            ['nama' => 'Muhammad Bilal', 'kelas' => '9 SMP', 'nis' => '0126405290'],
            ['nama' => 'Muhammad Fadhil Abdul Malik', 'kelas' => '9 SMP', 'nis' => '3121924981'],
            ['nama' => 'Ibnu Rusdi Ademar', 'kelas' => '11 SMA', 'nis' => '0109356646'],
            ['nama' => 'Muhammad Ghanim', 'kelas' => '9 SMP', 'nis' => '3125346188'],
            ['nama' => 'Muhammad Khalifah Akhyar', 'kelas' => '9 SMP', 'nis' => '3129355873'],
            ['nama' => 'Muhammad Zauzan Uruban', 'kelas' => '6 SD', 'nis' => '3149087515'],
            ['nama' => 'Nawaf', 'kelas' => '8 SMP', 'nis' => '0133418700'],
            ['nama' => 'Romulus Askha Juna Budiharto', 'kelas' => '7 SMP', 'nis' => null],
            ['nama' => 'Bilal A', 'kelas' => '7 SMP', 'nis' => null],
            ['nama' => 'Muhammad Ardi Heriawan', 'kelas' => '12 SMA', 'nis' => null],
        ];

        foreach ($data as $index => $item) {
            $kelas = Kelas::firstOrCreate(['nama' => $item['kelas']]);
            
            $nis = $item['nis'] ? $item['nis'] : 'TBD-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            Santri::create([
                'nama' => $item['nama'],
                'nis' => $nis,
                'kelas_id' => $kelas->id,
                'jenis_kelamin' => 'L',
            ]);
        }
    }
}
