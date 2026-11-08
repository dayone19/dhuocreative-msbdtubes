<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    private const FOLDER_JAWABAN = 'pengumpulan_tugas';

    public function index()
    {
        $idTentor    = auth()->id();
        $kolomStatus = $this->kolomStatus();

        $kelasOptions = $this->kelasTentor($idTentor)->get();

        $tugas = DB::table('tugas')
            ->join('kelas', 'tugas.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as siswa', 'kelas.id_siswa', '=', 'siswa.id_user')
            ->where('tugas.id_tentor', $idTentor)
            ->orderByDesc('tugas.created_at')
            ->select(
                'tugas.id_tugas',
                'tugas.id_kelas',
                'tugas.judul',
                'tugas.deskripsi',
                'tugas.deadline',
                'tugas.created_at',
                DB::raw('tugas.tipe::text as tipe'),
                'kursus.nama_kursus as kursus',
                'kelas.id_siswa',
                'siswa.nama as siswa'
            )
            ->get();

        $kirim = DB::table('pengumpulan_tugas')
            ->whereIn('id_tugas', $tugas->pluck('id_tugas'))
            ->orderBy('waktu_pengumpulan')
            ->select(
                'id_pengumpulan', 'id_tugas', 'id_siswa', 'file_jawaban', 'waktu_pengumpulan',
                DB::raw("{$kolomStatus}::text as status")
            )
            ->get();

        $terbaru = [];
        foreach ($kirim as $k) {
            $terbaru[$k->id_tugas . '-' . $k->id_siswa] = $k;
        }

        $nilai = DB::table('nilai')
            ->whereIn('id_pengumpulan', $kirim->pluck('id_pengumpulan'))
            ->pluck('nilai', 'id_pengumpulan');

        $daftarTugas = $tugas->map(function ($t) use ($terbaru, $nilai) {
            $k = $terbaru[$t->id_tugas . '-' . $t->id_siswa] ?? null;
            $n = $k ? ($nilai[$k->id_pengumpulan] ?? null) : null;

            return [
                'id'             => $t->id_tugas,
                'id_kelas'       => $t->id_kelas,
                'judul'          => $t->judul,
                'judul_lower'    => mb_strtolower($t->judul),
                'kursus'         => $t->kursus,
                'siswa'          => $t->siswa,
                'tipe'           => $t->tipe,
                'deskripsi'      => $t->deskripsi,
                'deadline'       => Carbon::parse($t->deadline)->format('Y-m-d\TH:i'),
                'tanggal'        => Carbon::parse($t->created_at)->format('Y-m-d H:i:s'),
                'tanggal_tampil' => Carbon::parse($t->created_at)->format('d/m/Y'),
                'status'         => ($k && $k->status) ? str_replace('_', ' ', $k->status) : 'tidak kumpul',
                'id_pengumpulan' => $k->id_pengumpulan ?? '',
                'file_nama'      => $k ? preg_replace('/^\d+_/', '', basename($k->file_jawaban)) : '',
                'file_url'       => $k ? route('tentor.tugas.file', ['pengumpulan' => $k->id_pengumpulan]) : '',
                'nilai'          => $n !== null ? rtrim(rtrim(number_format((float) $n, 2, '.', ''), '0'), '.') : '',
            ];
        })->all();

        $kursusFilter = collect($daftarTugas)->pluck('kursus')->unique()->sort()->values()->all();

        return view('tentor.tugas', compact('daftarTugas', 'kelasOptions', 'kursusFilter'));
    }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'judul'     => 'required|string|max:150',
            'tipe'      => 'required|in:tugas,projek',
            'id_kelas'  => 'required|integer',
            'deadline'  => 'required|date',
            'deskripsi' => 'nullable|string',
        ], $this->pesan());

        $punyaKelas = $this->kelasTentor(auth()->id())
            ->where('kelas.id_kelas', $data['id_kelas'])
            ->exists();
        abort_unless($punyaKelas, 403);

        DB::transaction(function () use ($data) {
            DB::statement('LOCK TABLE tugas IN SHARE ROW EXCLUSIVE MODE');

            DB::table('tugas')->insert([
                'id_tugas'   => (int) DB::table('tugas')->max('id_tugas') + 1,
                'id_kelas'   => $data['id_kelas'],
                'id_tentor'  => auth()->id(),
                'judul'      => $data['judul'],
                'deskripsi'  => $data['deskripsi'] ?? '',
                'deadline'   => $data['deadline'],
                'file_tugas' => '',           
                'created_at' => now(),
                'update_at'  => now(),        
                'tipe'       => $data['tipe'],
            ]);
        });

        return redirect()->route('tentor.tugas')->with('sukses', 'Tugas berhasil ditambahkan.');
    }

    public function update(Request $request, $idTugas)
    {
        $data = $request->validate([
            'judul'     => 'required|string|max:150',
            'tipe'      => 'required|in:tugas,projek',
            'deadline'  => 'required|date',
            'deskripsi' => 'nullable|string',
        ], $this->pesan());

        $milikku = DB::table('tugas')
            ->where('id_tugas', $idTugas)
            ->where('id_tentor', auth()->id())
            ->exists();
        abort_unless($milikku, 404);

        DB::table('tugas')->where('id_tugas', $idTugas)->update([
            'judul'     => $data['judul'],
            'tipe'      => $data['tipe'],
            'deadline'  => $data['deadline'],
            'deskripsi' => $data['deskripsi'] ?? '',
            'update_at' => now(),
        ]);

        return redirect()->route('tentor.tugas')->with('sukses', 'Data tugas berhasil diperbarui.');
    }

    public function nilai(Request $request, $idPengumpulan)
    {
        $data = $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ], [
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.numeric'  => 'Nilai harus berupa angka.',
            'nilai.min'      => 'Nilai minimal 0.',
            'nilai.max'      => 'Nilai maksimal 100.',
        ]);

        $milikku = DB::table('pengumpulan_tugas')
            ->join('tugas', 'pengumpulan_tugas.id_tugas', '=', 'tugas.id_tugas')
            ->where('pengumpulan_tugas.id_pengumpulan', $idPengumpulan)
            ->where('tugas.id_tentor', auth()->id())
            ->exists();
        abort_unless($milikku, 404);

        DB::transaction(function () use ($idPengumpulan, $data) {
            DB::statement('LOCK TABLE nilai IN SHARE ROW EXCLUSIVE MODE');

            $sudahAda = DB::table('nilai')->where('id_pengumpulan', $idPengumpulan)->exists();

            if ($sudahAda) {
                DB::table('nilai')->where('id_pengumpulan', $idPengumpulan)->update([
                    'nilai'         => $data['nilai'],
                    'id_tentor'     => auth()->id(),
                    'tanggal_nilai' => now(),
                ]);
            } else {
                DB::table('nilai')->insert([
                    'id_nilai'       => (int) DB::table('nilai')->max('id_nilai') + 1,
                    'id_pengumpulan' => $idPengumpulan,
                    'id_tentor'      => auth()->id(),
                    'nilai'          => $data['nilai'],
                    'feedback'       => null,
                    'tanggal_nilai'  => now(),
                ]);
            }
        });

        return redirect()->route('tentor.tugas')->with('sukses', 'Nilai berhasil disimpan.');
    }

    public function fileJawaban($idPengumpulan)
    {
        $kirim = DB::table('pengumpulan_tugas')
            ->join('tugas', 'pengumpulan_tugas.id_tugas', '=', 'tugas.id_tugas')
            ->where('pengumpulan_tugas.id_pengumpulan', $idPengumpulan)
            ->where('tugas.id_tentor', auth()->id())
            ->select('pengumpulan_tugas.file_jawaban')
            ->first();

        abort_if(!$kirim, 404);

        $path = str_contains($kirim->file_jawaban, '/')
            ? $kirim->file_jawaban
            : self::FOLDER_JAWABAN . '/' . $kirim->file_jawaban;

        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response(
            $path,
            preg_replace('/^\d+_/', '', basename($kirim->file_jawaban))
        );
    }

    private function kelasTentor($idTentor)
    {
        return DB::table('kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as siswa', 'kelas.id_siswa', '=', 'siswa.id_user')
            ->where('kelas.status', 'aktif')
            ->whereIn('kelas.id_kelas', function ($q) use ($idTentor) {
                $q->select('id_kelas')->from('jadwal')->where('id_tentor', $idTentor);
            })
            ->orderBy('kursus.nama_kursus')
            ->orderBy('siswa.nama')
            ->select(
                'kelas.id_kelas',
                'kursus.nama_kursus as kursus',
                'siswa.nama as siswa',
                DB::raw('initcap(kelas.kelas::text) as jenis')
            );
    }

    private function kolomStatus(): string
    {
        return Schema::hasColumn('pengumpulan_tugas', 'status') ? 'status' : 'stauts';
    }

    private function pesan(): array
    {
        return [
            'judul.required'    => 'Judul tugas wajib diisi.',
            'tipe.required'     => 'Pilih jenis tugas.',
            'tipe.in'           => 'Jenis tugas harus Tugas atau Projek.',
            'id_kelas.required' => 'Pilih kursus dan siswa terlebih dahulu.',
            'deadline.required' => 'Deadline wajib diisi.',
        ];
    }
}