<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
 
    private const TOTAL_PERTEMUAN = 12;

    public function index()
    {
        $idTentor = auth()->id();
        $hariIni  = Carbon::today();

        // 1. Jadwal minggu ini 
        $senin  = $hariIni->copy()->startOfWeek();   
        $sabtu  = $senin->copy()->addDays(5);

        $jadwalMinggu = DB::table('jadwal')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->where('jadwal.id_tentor', $idTentor)
            ->where('jadwal.status', '!=', 'dibatalkan')
            ->whereBetween('jadwal.tanggal', [$senin->toDateString(), $sabtu->toDateString()])
            ->orderBy('jadwal.jam_mulai')
            ->select('jadwal.tanggal', 'jadwal.jam_mulai', 'jadwal.jam_selesai', $this->namaKelas())
            ->get();

        $namaHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwal   = [];

        foreach ($namaHari as $i => $hari) {
            $tanggal = $senin->copy()->addDays($i)->toDateString();

            $detail = $jadwalMinggu
                ->where('tanggal', $tanggal)
                ->map(fn ($j) => $j->nama_kelas . ', ' . $this->jam($j->jam_mulai, $j->jam_selesai))
                ->implode('; ');

            $jadwal[] = ['hari' => $hari, 'detail' => $detail ?: '-'];
        }

        $semuaJadwal = DB::table('jadwal')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->where('jadwal.id_tentor', $idTentor)
            ->where('kelas.status', 'aktif')
            ->orderBy('jadwal.tanggal')
            ->orderBy('jadwal.jam_mulai')
            ->select('jadwal.id_kelas', $this->namaKelas(), 'jadwal.tanggal', 'jadwal.jam_mulai', 'jadwal.jam_selesai')
            ->get()
            ->groupBy('id_kelas');         

        $jumlahSiswa = DB::table('siswa_kelas')
            ->where('status', 'aktif')
            ->whereIn('id_kelas', $semuaJadwal->keys())
            ->select('id_kelas', DB::raw('count(*) as total'))
            ->groupBy('id_kelas')
            ->pluck('total', 'id_kelas');

        $kelasSaya = [];
        $urutan    = 0;

        foreach ($semuaJadwal as $idKelas => $baris) {
            $pilih = $baris->first(fn ($r) => $r->tanggal >= $hariIni->toDateString())
                  ?? $baris->last();

            $kelasSaya[] = [
                'id'    => $idKelas,
                'nama'  => $pilih->nama_kelas,
                'jam'   => $this->jam($pilih->jam_mulai, $pilih->jam_selesai),
                'siswa' => $jumlahSiswa[$idKelas] ?? 0,
                'warna' => $urutan++ % 2 === 0 ? 'teal' : 'orange',
            ];
        }

        return view('tentor.kelas', compact('jadwal', 'kelasSaya'));
    }

    public function absensi(Request $request, $idKelas)
    {
        $kelas       = $this->kelasMilikTentor($idKelas);
        $jadwalKelas = $this->jadwalKelas($idKelas);

        $daftarPertemuan = [];
        for ($i = 1; $i <= self::TOTAL_PERTEMUAN; $i++) {
            $daftarPertemuan[$i] = $jadwalKelas->get($i - 1); 
        }

        $pertemuan = $request->query('pertemuan')
            ? (int) $request->query('pertemuan')
            : $this->pertemuanDefault($jadwalKelas);
        $pertemuan = max(1, min(self::TOTAL_PERTEMUAN, $pertemuan));

        $jadwalTerpilih = $daftarPertemuan[$pertemuan];

        $infoJadwal = $jadwalTerpilih
            ? Carbon::parse($jadwalTerpilih->tanggal)->locale('id')->translatedFormat('l, d F Y')
                . ', ' . $this->jam($jadwalTerpilih->jam_mulai, $jadwalTerpilih->jam_selesai)
            : 'Pertemuan ini belum dijadwalkan';

        $siswaKelas = DB::table('siswa_kelas')
            ->join('users', 'siswa_kelas.id_siswa', '=', 'users.id_user')
            ->where('siswa_kelas.id_kelas', $idKelas)
            ->where('siswa_kelas.status', 'aktif')
            ->orderBy('users.nama')
            ->select('users.id_user as id', 'users.nama')
            ->get();

        $statusTersimpan = $jadwalTerpilih
            ? DB::table('absensi_siswa')
                ->where('id_jadwal', $jadwalTerpilih->id_jadwal)
                ->pluck('status', 'id_siswa')
            : collect();

        $totalHadir = DB::table('absensi_siswa')
            ->whereIn('id_jadwal', $jadwalKelas->pluck('id_jadwal'))
            ->where('status', 'hadir')
            ->select('id_siswa', DB::raw('count(*) as total'))
            ->groupBy('id_siswa')
            ->pluck('total', 'id_siswa');

        $daftarSiswa = $siswaKelas->map(fn ($s) => [
            'id'     => $s->id,
            'nama'   => $s->nama,
            'status' => $statusTersimpan[$s->id] ?? null,
            'hadir'  => $totalHadir[$s->id] ?? 0,
        ])->all();

        return view('tentor.absensi', [
            'kelas'           => $kelas,
            'infoJadwal'      => $infoJadwal,
            'daftarPertemuan' => $daftarPertemuan,
            'pertemuan'       => $pertemuan,
            'jadwalTerpilih'  => $jadwalTerpilih,
            'daftarSiswa'     => $daftarSiswa,
            'totalPertemuan'  => self::TOTAL_PERTEMUAN,
        ]);
    }

    public function simpanAbsensi(Request $request, $idKelas)
    {
        $this->kelasMilikTentor($idKelas);

        $request->validate([
            'pertemuan'   => 'required|integer|between:1,' . self::TOTAL_PERTEMUAN,
            'absensi'     => 'required|array',
            'absensi.*'   => 'in:hadir,sakit,izin,alpha',  
        ], [
            'absensi.required' => 'Pilih status absensi untuk setiap siswa terlebih dahulu.',
            'absensi.*.in'     => 'Status absensi tidak valid.',
        ]);

        $pertemuan = (int) $request->input('pertemuan');
        $jadwal    = $this->jadwalKelas($idKelas)->get($pertemuan - 1);

        if (!$jadwal) {
            return back()->withErrors(['absensi' => "Pertemuan {$pertemuan} belum dijadwalkan."]);
        }

        $idSiswaKelas = DB::table('siswa_kelas')
            ->where('id_kelas', $idKelas)
            ->where('status', 'aktif')
            ->pluck('id_siswa');

        $absensi = $request->input('absensi');

        foreach ($idSiswaKelas as $idSiswa) {
            if (!isset($absensi[$idSiswa])) {
                return back()->withInput()
                    ->withErrors(['absensi' => 'Pilih status absensi untuk setiap siswa terlebih dahulu.']);
            }
        }

        DB::transaction(function () use ($idSiswaKelas, $absensi, $jadwal) {
            $idBerikutnya = (int) DB::table('absensi_siswa')->max('id_absensi');

            foreach ($idSiswaKelas as $idSiswa) {
                $sudahAda = DB::table('absensi_siswa')
                    ->where('id_jadwal', $jadwal->id_jadwal)
                    ->where('id_siswa', $idSiswa)
                    ->exists();

                if ($sudahAda) {
                    DB::table('absensi_siswa')
                        ->where('id_jadwal', $jadwal->id_jadwal)
                        ->where('id_siswa', $idSiswa)
                        ->update(['status' => $absensi[$idSiswa], 'waktu_absen' => now()]);
                } else {
                    DB::table('absensi_siswa')->insert([
                        'id_absensi'  => ++$idBerikutnya,
                        'id_jadwal'   => $jadwal->id_jadwal,
                        'id_siswa'    => $idSiswa,
                        'waktu_absen' => now(),
                        'status'      => $absensi[$idSiswa],
                    ]);
                }
            }
        });

        return redirect()
            ->route('tentor.absensi', ['kelas' => $idKelas, 'pertemuan' => $pertemuan])
            ->with('sukses', "Absensi pertemuan {$pertemuan} berhasil disimpan.");
    }

    private function kelasMilikTentor($idKelas)
    {
        $kelas = DB::table('kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->where('kelas.id_kelas', $idKelas)
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('jadwal')
                  ->whereColumn('jadwal.id_kelas', 'kelas.id_kelas')
                  ->where('jadwal.id_tentor', auth()->id());
            })
            ->select('kelas.id_kelas', $this->namaKelas(), 'kursus.nama_kursus')
            ->first();

        abort_if(!$kelas, 404);

        return $kelas;
    }

    private function jadwalKelas($idKelas)
    {
        return DB::table('jadwal')
            ->where('id_kelas', $idKelas)
            ->where('id_tentor', auth()->id())
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get()
            ->values();
    }

    private function pertemuanDefault($jadwalKelas): int
    {
        $hariIni = Carbon::today()->toDateString();

        foreach ($jadwalKelas as $i => $j) {
            if ($j->tanggal >= $hariIni) {
                return $i + 1;
            }
        }

        return max(1, $jadwalKelas->count());
    }

    private function namaKelas()
    {
        return DB::raw("kursus.nama_kursus || ' - ' || kelas.id_kelas || ' - ' || initcap(kelas.kelas::text) as nama_kelas");
    }

    private function jam($mulai, $selesai): string
    {
        return Carbon::parse($mulai)->format('H.i') . ' - ' . Carbon::parse($selesai)->format('H.i');
    }
}