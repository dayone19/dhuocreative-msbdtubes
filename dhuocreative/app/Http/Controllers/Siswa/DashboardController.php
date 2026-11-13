<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $idSiswa   = auth()->id();
        $namaSiswa = auth()->user()->nama;

        // Jadwal hari ini
        $jadwalHariIni = DB::table('jadwal')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as tentor', 'jadwal.id_tentor', '=', 'tentor.id_user')
            ->where('kelas.id_siswa', $idSiswa)
            ->whereDate('jadwal.tanggal', today())
            ->where('jadwal.status', '!=', 'dibatalkan')
            ->orderBy('jadwal.jam_mulai')
            ->select(
                'jadwal.id_jadwal',
                'jadwal.jam_mulai',
                'jadwal.jam_selesai',
                'jadwal.pertemuan_ke',
                'jadwal.status as status_jadwal',
                'kursus.nama_kursus',
                'kelas.kelas as jenis_kelas',
                'tentor.nama as nama_tentor'
            )
            ->get()
            ->map(function ($j) {
                $j->jam = substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5);

                $jenis = strtolower((string) $j->jenis_kelas);
                if (str_contains($jenis, 'online')) {
                    $j->badge_class = 'sd-badge-online';
                    $j->badge_text  = 'Online';
                } elseif (str_contains($jenis, 'private')) {
                    $j->badge_class = 'sd-badge-private';
                    $j->badge_text  = 'Private';
                } else {
                    $j->badge_class = 'sd-badge-home';
                    $j->badge_text  = 'Home';
                }

                return $j;
            });

        $pengumumanTerbaru = DB::table('pengumuman')
            ->join('users', 'pengumuman.id_user', '=', 'users.id_user')
            ->whereDate('pengumuman.tanggal', today())
            ->orderByDesc('pengumuman.tanggal')
            ->orderByDesc('pengumuman.id_pengumuman')
            ->select(
                'pengumuman.judul',
                'pengumuman.isi',
                'pengumuman.tanggal',
                'users.nama',
                'users.role'
            )
            ->first();

        $kursusAktif = DB::table('kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->where('kelas.id_siswa', $idSiswa)
            ->where('kelas.status', 'aktif')
            ->select(
                'kelas.id_kelas',
                'kursus.nama_kursus',
                'kursus.jumlah_pertemuan',
                'kelas.kelas as jenis_kelas'
            )
            ->get()
            ->map(function ($k) {
                $selesai = DB::table('jadwal')
                    ->where('id_kelas', $k->id_kelas)
                    ->where('status', 'selesai')
                    ->count();

                $target = (int) ($k->jumlah_pertemuan ?: 15);
                $k->persen = $target > 0 ? min(100, (int) round($selesai / $target * 100)) : 0;

                $k->nama_tentor = DB::table('jadwal')
                    ->join('users', 'jadwal.id_tentor', '=', 'users.id_user')
                    ->where('jadwal.id_kelas', $k->id_kelas)
                    ->where('jadwal.status', '!=', 'dibatalkan')
                    ->orderByDesc('jadwal.tanggal')
                    ->value('users.nama') ?? '-';

                return $k;
            });

        $kursusUtama = $kursusAktif->first();

        return view('siswa.dashboard', compact(
            'namaSiswa',
            'jadwalHariIni',
            'kursusAktif',
            'kursusUtama',
            'pengumumanTerbaru'
        ));
    }
}