<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_dosen' => 1,
                'nip' => 234171, 
                'nama' => 'Bu Priska',
                'username' => 'Priska',
                'password' => Hash::make('12345'),
                'created_at' => NOW()
            ],
            [
                'id_dosen' => 2,
                'nip' => 234172, 
                'nama' => 'Bu Vivin',
                'username' => 'Vivin',
                'password' => Hash::make('12345'),
                'created_at' => NOW()
            ],
            [
                'id_dosen' => 3,
                'nip' => 234173, 
                'nama' => 'Mam Farida',
                'username' => 'Farida',
                'password' => Hash::make('12345'),
                'created_at' => NOW()
            ]
        ];

        DB::table('dosen')->insert($data);
    }
}
