<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    private const RINGKAS = 5;

    public function index(Request $request)
    {
        $idSiswa  = auth()->id();
        $sekarang = now()->format('H:i:s');

        $jadwalHariIni = DB::table('jadwal')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as tentor', 'jadwal.id_tentor', '=', 'tentor.id_user')
            ->leftJoin('absensi_siswa as a', function ($j) use ($idSiswa) {
                $j->on('a.id_jadwal', '=', 'jadwal.id_jadwal')
                    ->where('a.id_siswa', '=', $idSiswa);
            })
            ->where('kelas.id_siswa', $idSiswa)
            ->whereDate('jadwal.tanggal', today())
            ->where('jadwal.status', '!=', 'dibatalkan')
            ->orderBy('jadwal.jam_mulai')
            ->select(
                'jadwal.id_jadwal',
                'kursus.nama_kursus',
                'tentor.nama as nama_tentor',
                DB::raw('kelas.kelas::text as jenis'),
                'jadwal.jam_mulai',
                'jadwal.jam_selesai',
                DB::raw('a.status::text as status_absen')
            )
            ->get()
            ->map(function ($j) use ($sekarang) {
                $j->jam = $this->jam($j->jam_mulai, $j->jam_selesai);
                $j->berlangsung = $j->jam_mulai <= $sekarang && $sekarang <= $j->jam_selesai;
                $j->sudah_absen = $j->status_absen !== null;
                $j->bisa_absen = !$j->sudah_absen && $j->berlangsung;
                return $j;
            });

        $riwayatQuery = DB::table('absensi_siswa as a')
            ->join('jadwal as j', 'a.id_jadwal', '=', 'j.id_jadwal')
            ->join('kelas as k', 'j.id_kelas', '=', 'k.id_kelas')
            ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
            ->where('a.id_siswa', $idSiswa)
            ->orderByDesc('j.tanggal')
            ->orderByDesc('j.jam_mulai')
            ->select('j.tanggal', 'j.jam_mulai', 'j.jam_selesai', 'ku.nama_kursus', 'a.status');

        $totalRiwayat = DB::table('absensi_siswa')->where('id_siswa', $idSiswa)->count();
        $semua        = $request->boolean('semua');

        $label = ['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpa'];

        if ($semua) {
            $riwayat = $riwayatQuery
                ->paginate(5)
                ->withQueryString();

            $riwayat->setCollection(
                $riwayat->getCollection()->map(function ($r) use ($label) {
                    $r->tanggal_teks = Carbon::parse($r->tanggal)
                        ->locale('id')
                        ->translatedFormat('j F Y');
                    $r->jam = $this->jam($r->jam_mulai, $r->jam_selesai);
                    $r->status_teks = $label[$r->status] ?? ucfirst($r->status);
                    return $r;
                })
            );
        } else {
            $riwayat = $riwayatQuery
                ->limit(self::RINGKAS)
                ->get()
                ->map(function ($r) use ($label) {
                    $r->tanggal_teks = Carbon::parse($r->tanggal)
                        ->locale('id')
                        ->translatedFormat('j F Y');
                    $r->jam = $this->jam($r->jam_mulai, $r->jam_selesai);
                    $r->status_teks = $label[$r->status] ?? ucfirst($r->status);
                    return $r;
                });
        }

        $tanggalHariIni = Carbon::today()->locale('id')->translatedFormat('l, j F Y');

        return view('siswa.absensi', [
            'jadwalHariIni'  => $jadwalHariIni,
            'riwayat'        => $riwayat,
            'totalRiwayat'   => $totalRiwayat,
            'semua'          => $semua,
            'ringkas'        => self::RINGKAS,
            'tanggalHariIni' => $tanggalHariIni,
        ]);
    }

    public function absen(Request $request)
    {
        $idSiswa = auth()->id();

        $data = $request->validate([
            'id_jadwal' => 'required|integer',
            'status'    => 'required|in:hadir,izin,sakit',
        ], [
            'id_jadwal.required' => 'Jadwal tidak valid.',
            'status.required'    => 'Pilih status absensi.',
            'status.in'          => 'Status absensi tidak valid.',
        ]);

        $jadwal = DB::table('jadwal')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->where('jadwal.id_jadwal', $data['id_jadwal'])
            ->where('kelas.id_siswa', $idSiswa)
            ->whereDate('jadwal.tanggal', today())
            ->where('jadwal.status', '!=', 'dibatalkan')
            ->select('jadwal.id_jadwal', 'jadwal.jam_mulai', 'jadwal.jam_selesai')
            ->first();

        abort_if(!$jadwal, 404);

        $sekarang = now()->format('H:i:s');
        if ($sekarang < $jadwal->jam_mulai || $sekarang > $jadwal->jam_selesai) {
            return back()->withErrors([
                'absensi' => 'Presensi hanya bisa dilakukan saat jam kelas berlangsung.',
            ]);
        }

        $sudah = DB::table('absensi_siswa')
            ->where('id_jadwal', $jadwal->id_jadwal)
            ->where('id_siswa', $idSiswa)
            ->exists();

        if ($sudah) {
            return back()->withErrors([
                'absensi' => 'Kamu sudah mengisi presensi untuk jadwal ini.',
            ]);
        }

        DB::transaction(function () use ($jadwal, $idSiswa, $data) {
            DB::statement('LOCK TABLE absensi_siswa IN SHARE ROW EXCLUSIVE MODE');

            DB::table('absensi_siswa')->insert([
                'id_absensi'  => ((int) DB::table('absensi_siswa')->max('id_absensi')) + 1,
                'id_jadwal'   => $jadwal->id_jadwal,
                'id_siswa'    => $idSiswa,
                'status'      => $data['status'],
                'waktu_absen' => now(),
            ]);
        });

        $teks = match ($data['status']) {
            'hadir' => 'Presensi hadir berhasil dicatat.',
            'izin'  => 'Presensi izin berhasil dicatat.',
            'sakit' => 'Presensi sakit berhasil dicatat.',
            default => 'Presensi berhasil dicatat.',
        };

        return back()->with('sukses', $teks);
    }

    private function jam($mulai, $selesai): string
    {
        return Carbon::parse($mulai)->format('H:i') . ' – ' . Carbon::parse($selesai)->format('H:i');
    }
}
