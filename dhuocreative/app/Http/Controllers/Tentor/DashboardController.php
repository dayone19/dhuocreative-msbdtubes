<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $idTentor = auth()->id(); // = id_user

        // jumlah kelas unik yang diajar tentor
        $totalKelas = DB::table('jadwal')
            ->where('id_tentor', $idTentor)
            ->distinct()
            ->count('id_kelas');

        // jumlah siswa aktif unik di kelas-kelas tentor
        $totalSiswa = DB::table('siswa_kelas')
            ->join('jadwal', 'siswa_kelas.id_kelas', '=', 'jadwal.id_kelas')
            ->where('jadwal.id_tentor', $idTentor)
            ->where('siswa_kelas.status', 'aktif')
            ->distinct()
            ->count('siswa_kelas.id_siswa');

        // pengumpulan sudah masuk tapi belum ada nilainya
        // NOTE: kolom di DB sebelumnya typo "stauts" -> jalankan RENAME dulu (lihat pengajuan_jadwal.sql)
        $tugasMenunggu = DB::table('pengumpulan_tugas')
            ->join('tugas', 'pengumpulan_tugas.id_tugas', '=', 'tugas.id_tugas')
            ->where('tugas.id_tentor', $idTentor)
            ->whereIn('pengumpulan_tugas.status', ['kumpul', 'terlambat'])
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('nilai')
                  ->whereColumn('nilai.id_pengumpulan', 'pengumpulan_tugas.id_pengumpulan');
            })
            ->count();

        // jadwal hari ini
        $rows = DB::table('jadwal')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as siswa', 'kelas.id_siswa', '=', 'siswa.id_user')
            ->where('jadwal.id_tentor', $idTentor)
            ->whereDate('jadwal.tanggal', today())
            ->where('jadwal.status', '!=', 'dibatalkan')
            ->orderBy('jadwal.jam_mulai')
            ->select(
                'jadwal.id_jadwal',
                'kelas.id_kelas',
                'kursus.nama_kursus',
                'siswa.nama as nama_siswa',
                DB::raw("initcap(kelas.kelas::text) as nama_kelas"),
                'jadwal.jam_mulai',
                'jadwal.jam_selesai',
                'jadwal.status'
            )
            ->get();

        // hitung kehadiran sekali jalan (hindari query per baris)
        $siswaPerKelas = DB::table('siswa_kelas')
            ->whereIn('id_kelas', $rows->pluck('id_kelas')->unique())
            ->where('status', 'aktif')
            ->selectRaw('id_kelas, count(*) as total')
            ->groupBy('id_kelas')
            ->pluck('total', 'id_kelas');

        $hadirPerJadwal = DB::table('absensi_siswa')
            ->whereIn('id_jadwal', $rows->pluck('id_jadwal'))
            ->where('status', 'hadir')
            ->selectRaw('id_jadwal, count(*) as total')
            ->groupBy('id_jadwal')
            ->pluck('total', 'id_jadwal');

        $jadwalHariIni = $rows->map(function ($j) use ($siswaPerKelas, $hadirPerJadwal) {
            $j->total_siswa = (int) ($siswaPerKelas[$j->id_kelas] ?? 0);
            $j->hadir       = (int) ($hadirPerJadwal[$j->id_jadwal] ?? 0);
            $j->persen      = $j->total_siswa > 0
                ? round($j->hadir / $j->total_siswa * 100)
                : 0;
            $j->jam         = str_replace(':', '.', substr($j->jam_mulai, 0, 5))
                . ' - ' . str_replace(':', '.', substr($j->jam_selesai, 0, 5));
            return $j;
        });

        return view('tentor.dashboard', compact(
            'totalKelas', 'totalSiswa', 'tugasMenunggu', 'jadwalHariIni'
        ));
    }
}