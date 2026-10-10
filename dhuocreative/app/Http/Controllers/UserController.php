<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // buat akun
    public function store(Request $request) 
    {
        // validasi user
        $request->validate([
            'nama'        => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:150'],
            'no_telp'     => ['required', 'string'],
            'pasword'     => [
                'required',
                'string',
                'min:7',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/^[a-zA-Z0-9]+$/^',
            ],
            'role'        => [
                'required',
                Rule::in(['admin', 'operator', 'tentor', 'siswa']),
            ],
            'tahun_masuk' => ['required', 'integer'],
            'status'      => [
                'required',
                Rule::in(['aktif', 'nonaltif']),
            ],
        ]);

        // buat id pengguna increment
        $idUser = (DB::table('users')->max('id_user') ?? 0) + 1;

        // input ke database
        DB::table('users')->insert([
            'id_user' => $idUser,
            'nama'    => $validate['nama'],
            'email'   => $validate['email'],
            'no_telp' => $validate['no_telp'],
            'pasword' => Hash::make($validate['pasword']),
            'role'    => $validate['role'],
            'tahun_masuk' => $validate['tahun_masuk'],
            'status'  => $validate['status'],
            'created_at' => now(),  
            'update_at' => now(),  
        ]);

        return back()->with('succes', 'Akun berhasil dibuat.');

    }
}
