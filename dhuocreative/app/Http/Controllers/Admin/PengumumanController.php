<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengumumanController extends Controller
{
    public function index()
    {
        $daftarPengumuman = DB::table('pengumuman as p')
            ->join('users as u', 'p.id_user', '=', 'u.id_user')
            ->select(
                'p.id_pengumuman',
                'p.id_user',
                'p.judul',
                'p.isi',
                'p.tanggal',
                'u.nama as nama_pembuat',
                'u.role as role_pembuat'
            )
            ->orderByDesc('p.tanggal')
            ->orderByDesc('p.id_pengumuman')
            ->get();

        return view('Admin.pengumuman', compact('daftarPengumuman'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        abort_unless(
            in_array($user->role, ['admin', 'operator', 'tentor']),
            403
        );

        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        DB::table('pengumuman')->insert([
            'id_user' => $user->id_user,
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'tanggal' => $validated['tanggal'],
        ]);

        return redirect()
            ->route('admin.pengumuman')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $pengumuman = DB::table('pengumuman')
            ->where('id_pengumuman', $id)
            ->first();

        abort_if(!$pengumuman, 404);

        abort_unless(
            $user->role === 'admin' ||
            (
                in_array($user->role, ['operator', 'tentor']) &&
                $pengumuman->id_user == $user->id_user
            ),
            403
        );

        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        DB::table('pengumuman')
            ->where('id_pengumuman', $id)
            ->update([
                'judul' => $validated['judul'],
                'isi' => $validated['isi'],
                'tanggal' => $validated['tanggal'],
            ]);

        return redirect()
            ->route('admin.pengumuman')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = auth()->user();

        $pengumuman = DB::table('pengumuman')
            ->where('id_pengumuman', $id)
            ->first();

        abort_if(!$pengumuman, 404);

        abort_unless(
            $user->role === 'admin' ||
            (
                in_array($user->role, ['operator', 'tentor']) &&
                $pengumuman->id_user == $user->id_user
            ),
            403
        );

        DB::table('pengumuman')
            ->where('id_pengumuman', $id)
            ->delete();

        return redirect()
            ->route('admin.pengumuman')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
