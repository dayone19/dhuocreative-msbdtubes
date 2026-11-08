<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengumumanController extends Controller
{
    /** Semua pengumuman (dari admin, operator, dan tentor), terbaru di atas */
    public function index()
    {
        $pengumuman = DB::table('pengumuman')
            ->join('users', 'pengumuman.id_user', '=', 'users.id_user')
            ->orderByDesc('pengumuman.tanggal')
            ->orderByDesc('pengumuman.id_pengumuman')
            ->select(
                'pengumuman.id_pengumuman',
                'pengumuman.id_user',
                'pengumuman.judul',
                'pengumuman.isi',
                'pengumuman.tanggal',
                'users.nama',
                DB::raw('users.role::text as role')
            )
            ->get();

        return view('tentor.pengumuman', compact('pengumuman'));
    }

    /** POST: buat pengumuman baru atas nama user yang login */
    public function simpan(Request $request)
    {
        $data = $this->validasi($request);

        DB::transaction(function () use ($data) {
            // id_pengumuman tidak auto-increment -> kunci tabel supaya id tidak kembar
            DB::statement('LOCK TABLE pengumuman IN SHARE ROW EXCLUSIVE MODE');

            DB::table('pengumuman')->insert([
                'id_pengumuman' => (int) DB::table('pengumuman')->max('id_pengumuman') + 1,
                'id_user'       => auth()->id(),
                'judul'         => $data['judul'],
                'isi'           => $data['isi'],
                'tanggal'       => $data['tanggal'],
            ]);
        });

        return redirect()->route('tentor.pengumuman')->with('sukses', 'Pengumuman berhasil dibuat.');
    }

    /** PUT: ubah pengumuman, hanya oleh pembuatnya */
    public function update(Request $request, $idPengumuman)
    {
        $data = $this->validasi($request);

        $diubah = DB::table('pengumuman')
            ->where('id_pengumuman', $idPengumuman)
            ->where('id_user', auth()->id())
            ->update([
                'judul'   => $data['judul'],
                'isi'     => $data['isi'],
                'tanggal' => $data['tanggal'],
            ]);

        abort_if($diubah === 0, 404); // bukan miliknya / tidak ada

        return redirect()->route('tentor.pengumuman')->with('sukses', 'Pengumuman berhasil diperbarui.');
    }

    /** DELETE: hapus pengumuman, hanya oleh pembuatnya */
    public function hapus($idPengumuman)
    {
        $dihapus = DB::table('pengumuman')
            ->where('id_pengumuman', $idPengumuman)
            ->where('id_user', auth()->id())
            ->delete();

        abort_if($dihapus === 0, 404);

        return redirect()->route('tentor.pengumuman')->with('sukses', 'Pengumuman berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'judul'   => 'required|string|max:150',
            'isi'     => 'required|string',
            'tanggal' => 'required|date',
        ], [
            'judul.required'   => 'Judul wajib diisi.',
            'judul.max'        => 'Judul maksimal 150 karakter.',
            'isi.required'     => 'Isi pengumuman wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date'     => 'Format tanggal tidak valid.',
        ]);
    }
}