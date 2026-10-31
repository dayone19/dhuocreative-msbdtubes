<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengumumanController extends Controller
{
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
                'users.role'
            )
            ->get();

        return view('tentor.pengumuman', compact('pengumuman'));
    }

    public function simpan(Request $request)
    {
        $request->validate($this->aturan(), $this->pesan());

        DB::transaction(function () use ($request) {
            DB::table('pengumuman')->insert([
                 'id_pengumuman' => (int) DB::table('pengumuman')->max('id_pengumuman') + 1,
                'id_user'       => auth()->id(),     
                'judul'         => $request->judul,
                'isi'           => $request->isi,
                'tanggal'       => $request->tanggal,
            ]);
        });

        return redirect()->route('tentor.pengumuman')->with('sukses', 'pengumuman berhasil dibuat.');
    }

    public function update(Request $request, $idPengumuman)
    {
        $this->pengumumanMilikSendiri($idPengumuman);
 
        $request->validate($this->aturan(), $this->pesan());
 
        DB::table('pengumuman')
            ->where('id_pengumuman', $idPengumuman)
            ->update([
                'judul'   => $request->judul,
                'isi'     => $request->isi,
                'tanggal' => $request->tanggal,
            ]);
 
        return redirect()->route('tentor.pengumuman')->with('sukses', 'Pengumuman berhasil diperbarui.');
    }

    public function hapus($idPengumuman)
    {
          $this->pengumumanMilikSendiri($idPengumuman);
 
        DB::table('pengumuman')->where('id_pengumuman', $idPengumuman)->delete();
 
        return redirect()->route('tentor.pengumuman')->with('sukses', 'Pengumuman berhasil dihapus.');
    }
  
    private function aturan(): array
    {
        return [
            'judul'   => 'required|string|max:150',
            'isi'     => 'required|string',
            'tanggal' => 'required|date',
        ];
    }

    private function pesan(): array
    {
        return [
            'judul.required'   => 'Judul pengumuman wajib diisi.',
            'isi.required'     => 'Isi pengumuman wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
        ];
    }

    private function pengumumanMilikSendiri($idPengumuman)
    {
        $pengumuman = DB::table('pengumuman')->where('id_pengumuman', $idPengumuman)->first();

        abort_if(!$pengumuman, 404);
        abort_unless((int) $pengumuman->id_user === (int) auth()->id(), 403);

        return $pengumuman;
    }
}
