<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_mahasiswa' => 1,
                'nim' => '2341760001', 
                'nama' => 'Muhammad Rohman Al K',
                'username' => '2341760001',
                'password' => Hash::make('12345'),
                'created_at' => NOW()
            ],
            [
                'id_mahasiswa' => 2,
                'nim' => '2341760002', 
                'nama' => 'Muhammad Rosyid',
                'username' => '2341760002',
                'password' => Hash::make('12345'),
                'created_at' => NOW()
            ],
            [
                'id_mahasiswa' => 3,
                'nim' => '2341760003', 
                'nama' => 'Bagas Satria YN',
                'username' => '2341760003',
                'password' => Hash::make('12345'),
                'created_at' => NOW()
            ]
        ];

        DB::table('mahasiswa')->insert($data);
    }
}
