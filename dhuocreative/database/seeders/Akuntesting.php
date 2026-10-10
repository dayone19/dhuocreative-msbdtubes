<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Akuntesting extends Seeder
{
    // akun tes forgot password
    public function run(): void
    {
         DB::table('users')->insert([
            'id_user' => 5,
            'nama' => 'Deyoan',
            'email' => 'deyoansalsabila43@gmail.com',
            'no_telp' => '081375075091',
            'pasword' => Hash::make('Deyoan123'),
            'role' => 'tentor',
            'tahun_masuk' => 2026,
            'status' => 'aktif', 
            'created_at' => now(),
            'update_at' => now(),
        ]);
    }
}
