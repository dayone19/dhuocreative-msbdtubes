<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function show($idTugas)
    {
        $tugas = DB::table('tugas')
            ->join('kelas', 'tugas.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->where('tugas.id_tugas', $idTugas)
            ->where('tugas.id_tentor', auth()->id())
            ->select('tugas.id_tugas', 'tugas.judul', $this->namaKelas())
            ->first();

        abort_if(!$tugas, 404);

        $pengumpulan = DB::table('pengumpulan_tugas')
            ->join('users', 'pengumpulan_tugas.id_siswa', '=', 'users.id_user')
            ->leftJoin('nilai',  'nilai.id_pengumpulan', '=', 'pengumpulan_tugas.id_pengumpulan')
            ->where('pengumpulan_tugas.id_tugas', $idTugas)
            ->whereIn('pengumpulan_tugas.status', ['kumpul', 'terlambat'])
            ->orderBy('users.nama')
            ->select(
                'pengumpulan_tugas.id_pengumpulan',
                'pengumpulan_tugas.id_siswa',
                'pengumpulan_tugas.file_jawaban',
                'pengumpulan_tugas.waktu_pengumpulan',
                'users.nama',
                'nilai.nilai'
            )
            ->get();

        $daftarNilai = $pengumpulan->map(fn ($p) => [
            'id'      => $p->id_pengumpulan,
            'nama'    => $p->nama,
            'file'    => $p->file_jawaban,
            'tanggal' => date('d/m/Y H:i', strtotime($p->waktu_pengumpulan)),
            'nilai'   => $p->nilai !== null ? (float) $p->nilai : null,
            'url'     => route('tentor.nilai.simpan', ['pengumpulan' => $p->id_pengumpulan]),
        ])->all();

        return view('tentor.nilai', compact('tugas', 'daftarNilai'));
    }

    public function simpan(Request $request, $idPengumpulan)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ], [
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.numeric'  => 'Nilai harus berupa angka.',
            'nilai.min'      => 'Nilai minimal 0.',
            'nilai.max'      => 'Nilai maksimal 100.',
        ]);

        $pengumpulan = DB::table('pengumpulan_tugas')
            ->join('tugas', 'pengumpulan_tugas.id_tugas', '=', 'tugas.id_tugas')
            ->where('pengumpulan_tugas.id_pengumpulan', $idPengumpulan)
            ->where('tugas.id_tentor', auth()->id())
            ->select('pengumpulan_tugas.id_pengumpulan', 'pengumpulan_tugas.id_tugas')
            ->first();

        abort_if(!$pengumpulan, 404);

        DB::transaction(function () use ($request, $idPengumpulan) {
            $sudahAda = DB::table('nilai')->where('id_pengumpulan', $idPengumpulan)->exists();


            if ($sudahAda) {
                DB::table('nilai')
                    ->where('id_pengumpulan', $idPengumpulan)
                    ->update([
                        'nilai' => $request->nilai,
                        'id_tentor' => auth()->id(),
                        'tanggal_nilai' => now(),
                    ]);
            } else {
                DB::table('nilai')->insert([
                    'id_nilai'   => (int) DB::table('nilai')->max('id_nilai') + 1,
                    'id_pengumpulan' => $idPengumpulan,
                    'id_tentor' => auth()->id(),
                    'nilai' => $request->nilai,
                    'tanggal_nilai' => now(),
                ]);
            }
        });

        return redirect()
            ->route('tentor.nilai', ['tugas' => $pengumpulan->id_tugas])
            ->with('sukses', 'Nilai berhasil disimpan.');
    }

    private function namaKelas()
    {
        return DB::raw("kursus.nama_kursus || ' - ' || kelas.id_kelas || ' - ' || initcap(kelas.kelas::text) as nama_kelas");
    }
}
