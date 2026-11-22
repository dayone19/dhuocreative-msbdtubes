<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ManajemenAkunController extends Controller
{
    // nampilin semua akun
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('nama', 'ILIKE', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $akun = $query
            ->orderBy('nama', 'asc')
            ->paginate(5)
            ->withQueryString();

        return view('admin.manajemenAkun', compact('akun'));
    }

    // edit akun
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'role' => 'required|in:admin,operator,tentor,siswa',
            'status' => 'required|in:aktif,nonaktif',
            'no_telp' => 'nullable|string|max:20',
            'tahun_masuk' => 'required|integer|min:2000|max:2100'
        ]);

        // UPDATE users SET kolom dibawah WHERE id_user = ?
        DB::table('users')
            ->where('id_user', $id)
            ->update([
                'nama' => $request->nama,
                'role' => $request->role,
                'status' => $request->status,
                'no_telp' => $request->no_telp,
                'tahun_masuk' => $request->tahun_masuk
            ]);

        return redirect()
            ->route('admin.manajemenAkun')
            ->with('success', 'Data akun berhasil diperbarui!');
    }

    // non akitkan akun
    public function nonaktifkan($id)
    {
        // UPDATE users SET status = 'nonaktif' WHERE id_user = ?
        DB::table('users')
            ->where('id_user', $id)
            ->update([
                'status' => 'nonaktif'
            ]);

        return redirect()
            ->route('admin.manajemenAkun')
            ->with('success', 'Akun berhasil dinonaktifkan!');
    }

    // aktifkan akun
    public function aktifkan($id)
    {
        // UPDATE users SET status = 'aktif' WHERE id_user = ?
        DB::table('users')
            ->where('id_user', $id)
            ->update([
                'status' => 'aktif'
            ]);

        return redirect()
            ->route('admin.manajemenAkun')
            ->with('success', 'Akun berhasil diaktifkan!');
    }

    // buat akun
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'no_telp' => 'nullable|string|max:20',
            'role' => 'required|in:admin,operator,tentor,siswa',
            'tahun_masuk' => 'required|integer|min:2000|max:2100',
            // 'pasword' => Hash::make('123'),
        ]);

        // INSERT INTO users
        DB::table('users')->insert([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'pasword' => Hash::make('123'),
            'role' => $request->role,
            'tahun_masuk' => $request->tahun_masuk,
            'status' => 'aktif',
            'created_at' => now(),
            'update_at' => now()
        ]);

        return redirect()
            ->route('admin.manajemenAkun')
            ->with('success', 'Akun berhasil dibuat!');
    }
}
