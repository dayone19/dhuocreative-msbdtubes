<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Userseeder extends Seeder
{
    // buat akun awal 
    public function run(): void
    {
        $users = [
            [
                'id_user' => 1,
                'nama' => 'Administrator',
                'email' => 'admin@dhuocreative.com',
                'no_telp' => '081234567801',
                'pasword' => Hash::make('Admin123'),
                'role' => 'admin',
                'tahun_masuk' => 2026,
                'status' => 'aktif',
            ],
            [
                'id_user' => 2,
                'nama' => 'Operator',
                'email' => 'operator@dhuocreative.com',
                'no_telp' => '081234567802',
                'pasword' => Hash::make('Operator123'),
                'role' => 'operator',
                'tahun_masuk' => 2026,
                'status' => 'aktif',
            ],
            [
                'id_user' => 3,
                'nama' => 'Tentor',
                'email' => 'tentor@dhuocreative.com',
                'no_telp' => '081234567803',
                'pasword' => Hash::make('Tentor123'),
                'role' => 'tentor',
                'tahun_masuk' => 2026,
                'status' => 'aktif',
            ],
            [
               'id_user' => 4,
                'nama' => 'Siswa',
                'email' => 'siswa@dhuocreative.com',
                'no_telp' => '081234567804',
                'pasword' => Hash::make('Siswa123'),
                'role' => 'siswa',
                'tahun_masuk' => 2026,
                'status' => 'aktif', 
            ],
            [
               'id_user' => 5,
                'nama' => 'Deyoan',
                'email' => 'deyoansalsabila43@gmail.com',
                'no_telp' => '081375075091',
                'pasword' => Hash::make('Deyoan123'),
                'role' => 'tentor',
                'tahun_masuk' => 2026,
                'status' => 'aktif', 
            ],
        ];

        foreach ($users as $user) {
            $email = $user['email'];

            // cek akun berdasarkan email
            $exists = DB::table('users')
                ->where('email', $email)
                ->exists();

            // tambahkan akun kalau blm ada email yang sama terdaftar
            if (! $exists) {
                $user['created_at'] = now();
                $user['update_at'] = now();

                DB::table('users')->insert($user);
            }
        }
    }
}
