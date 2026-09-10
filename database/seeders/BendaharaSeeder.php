<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BendaharaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'bendahara@suntri.com'],
            [
                'name' => 'Bendahara Utama',
                'password' => bcrypt('password'),
                'role_id' => 7,
                'is_active' => true
            ]
        );
    }
}
