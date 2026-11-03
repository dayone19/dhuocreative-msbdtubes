<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TugasController extends Controller
{
    public function index()
    {
        $idTentor = auth()->id();

        $tugas = DB::table('tugas')
            ->join('kelas', 'tugas.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users', 'kelas.id_siswa', '=', 'users.id_user')          
            ->leftJoin('pengumpulan_tugas', function ($j) {
                $j->on('pengumpulan_tugas.id_tugas', '=', 'tugas.id_tugas')
                  ->on('pengumpulan_tugas.id_siswa', '=', 'kelas.id_siswa');
            })
            ->leftJoin('nilai', 'nilai.id_pengumpulan', '=', 'pengumpulan_tugas.id_pengumpulan')
            ->where('tugas.id_tentor', $idTentor)             
            ->orderByDesc('tugas.created_at')                
            ->select(
                'tugas.id_tugas',
                'tugas.judul',
                'tugas.tipe',
                'tugas.deskripsi',
                'tugas.deadline',
                'tugas.created_at',
                'kursus.nama_kursus',
                'users.nama as nama_siswa',
                'pengumpulan_tugas.id_pengumpulan',
                'pengumpulan_tugas.status as status_pengumpulan',
                'nilai.nilai'
            )
            ->get();

        $kelasTentor = $this->kelasTentor($idTentor);

        $kursusTentor = $kelasTentor->unique('id_kursus')->values();

        $namaKursus = $kelasTentor->pluck('nama_kursus')->merge($tugas->pluck('nama_kursus'))->unique()->values();
        $namaSiswa  = $kelasTentor->pluck('nama_siswa')->merge($tugas->pluck('nama_siswa'))->unique()->values();

        return view('tentor.tugas', compact('tugas', 'kelasTentor', 'kursusTentor', 'namaKursus', 'namaSiswa'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:150',
            'tipe'      => 'required|in:tugas,projek',          
            'id_kelas'  => 'required|integer',
            'deadline'  => 'required|date',
            'deskripsi' => 'required|string',
        ], $this->pesan());

        $idKelas = (int) $request->id_kelas;
        abort_unless($this->kelasTentor(auth()->id())->pluck('id_kelas')->contains($idKelas), 403);

        DB::transaction(function () use ($request, $idKelas) {
            DB::table('tugas')->insert([
                'id_tugas'   => (int) DB::table('tugas')->max('id_tugas') + 1,
                'id_kelas'   => $idKelas,
                'id_tentor'  => auth()->id(),
                'judul'      => $request->judul,
                'deskripsi'  => $request->deskripsi,
                'deadline'   => Carbon::parse($request->deadline),
                'file_tugas' => '',
                'tipe'       => $request->tipe,
                'created_at' => now(),
                'update_at'  => now(),                          
            ]);
        });

        return redirect()->route('tentor.tugas')->with('sukses', 'Tugas berhasil dibuat.');
    }

    public function update(Request $request, $idTugas)
    {
        $this->tugasMilikTentor($idTugas);

        $request->validate([
            'judul'     => 'required|string|max:150',
            'tipe'      => 'required|in:tugas,projek',
            'deadline'  => 'required|date',
            'deskripsi' => 'required|string',
        ], $this->pesan());

        DB::table('tugas')->where('id_tugas', $idTugas)->update([
            'judul'     => $request->judul,
            'tipe'      => $request->tipe,
            'deadline'  => Carbon::parse($request->deadline),
            'deskripsi' => $request->deskripsi,
            'update_at' => now(),
        ]);

        return redirect()->route('tentor.tugas')->with('sukses', 'Tugas berhasil diperbarui.');
    }

    public function simpanNilai(Request $request, $idTugas)
    {
        $tugas = DB::table('tugas')
            ->join('kelas', 'tugas.id_kelas', '=', 'kelas.id_kelas')
            ->where('tugas.id_tugas', $idTugas)
            ->where('tugas.id_tentor', auth()->id())
            ->select('tugas.id_tugas', 'kelas.id_siswa')
            ->first();

        abort_if(!$tugas, 404);

        $request->validate([
            'nilai' => 'required|numeric|between:0,100',
        ], [
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.numeric'  => 'Nilai harus berupa angka.',
            'nilai.between'  => 'Nilai harus berada di antara 0 sampai 100.',
        ]);

        $pengumpulan = DB::table('pengumpulan_tugas')
            ->where('id_tugas', $tugas->id_tugas)
            ->where('id_siswa', $tugas->id_siswa)
            ->whereIn('status', ['kumpul', 'terlambat'])
            ->orderByDesc('waktu_pengumpulan')
            ->first();

        if (!$pengumpulan) {
            return redirect()->route('tentor.tugas')
                ->withErrors(['nilai' => 'Siswa belum mengumpulkan tugas ini, jadi belum bisa dinilai.']);
        }

        DB::transaction(function () use ($request, $pengumpulan) {
            $sudahAda = DB::table('nilai')->where('id_pengumpulan', $pengumpulan->id_pengumpulan)->exists();

            if ($sudahAda) {
                DB::table('nilai')
                    ->where('id_pengumpulan', $pengumpulan->id_pengumpulan)
                    ->update([
                        'nilai'         => $request->nilai,
                        'id_tentor'     => auth()->id(),
                        'tanggal_nilai' => now(),
                    ]);
            } else {
                DB::table('nilai')->insert([
                    'id_nilai'       => (int) DB::table('nilai')->max('id_nilai') + 1,
                    'id_pengumpulan' => $pengumpulan->id_pengumpulan,
                    'id_tentor'      => auth()->id(),
                    'nilai'          => $request->nilai,
                    'tanggal_nilai'  => now(),
                ]);
            }
        });

        return redirect()->route('tentor.tugas')->with('sukses', 'Nilai berhasil disimpan.');
    }

    private function pesan(): array
    {
        return [
            'judul.required'     => 'Judul tugas wajib diisi.',
            'tipe.required'      => 'Pilih jenis tugas.',
            'tipe.in'            => 'Jenis tugas tidak valid.',
            'id_kelas.required'  => 'Pilih kursus dan siswa terlebih dahulu.',
            'deadline.required'  => 'Deadline wajib diisi.',
            'deadline.date'      => 'Format deadline tidak valid.',
            'deskripsi.required' => 'Deskripsi tugas wajib diisi.',
        ];
    }

    private function tugasMilikTentor($idTugas)
    {
        $tugas = DB::table('tugas')
            ->where('id_tugas', $idTugas)
            ->where('id_tentor', auth()->id())
            ->first();

        abort_if(!$tugas, 404);

        return $tugas;
    }

    private function kelasTentor($idTentor)
    {
        return DB::table('kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users', 'kelas.id_siswa', '=', 'users.id_user')
            ->where('kelas.status', 'aktif')
            ->whereExists(function ($q) use ($idTentor) {
                $q->select(DB::raw(1))
                  ->from('jadwal')
                  ->whereColumn('jadwal.id_kelas', 'kelas.id_kelas')
                  ->where('jadwal.id_tentor', $idTentor);
            })
            ->orderBy('kursus.nama_kursus')
            ->orderBy('users.nama')
            ->select(
                'kelas.id_kelas',
                'kursus.id_kursus',
                'kursus.nama_kursus',
                'users.nama as nama_siswa'
            )
            ->get();
    }
}