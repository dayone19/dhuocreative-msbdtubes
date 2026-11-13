<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $idTentor = auth()->id(); 

        $kelasTentor = function () use ($idTentor) {
            return DB::table('kelas')
                ->where('kelas.status', 'aktif')
                ->whereExists(function ($q) use ($idTentor) {
                    $q->select(DB::raw(1))
                      ->from('jadwal')
                      ->whereColumn('jadwal.id_kelas', 'kelas.id_kelas')
                      ->where('jadwal.id_tentor', $idTentor);
                });
        };

        $totalKelas = $kelasTentor()->count();

        $totalSiswa = $kelasTentor()->distinct()->count('kelas.id_siswa');

        $tugasMenunggu = DB::table('pengumpulan_tugas')
            ->join('tugas', 'pengumpulan_tugas.id_tugas', '=', 'tugas.id_tugas')
            ->join('kelas', 'tugas.id_kelas', '=', 'kelas.id_kelas')
            ->whereColumn('pengumpulan_tugas.id_siswa', 'kelas.id_siswa')
            ->where('tugas.id_tentor', $idTentor)
            ->whereIn('pengumpulan_tugas.status', ['terkumpul', 'terlambat'])
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
                'jadwal.jam_selesai'
            )
            ->get();

        $idKelasHariIni = $rows->pluck('id_kelas')->unique();

        $totalPertemuan = DB::table('jadwal')
            ->whereIn('id_kelas', $idKelasHariIni)
            ->where('id_tentor', $idTentor)
            ->where('status', '!=', 'dibatalkan')
            ->selectRaw('id_kelas, count(*) as total')
            ->groupBy('id_kelas')
            ->pluck('total', 'id_kelas');

        $hadirPerKelas = DB::table('absensi_siswa as a')
            ->join('jadwal as j', 'a.id_jadwal', '=', 'j.id_jadwal')
            ->join('kelas as k', 'j.id_kelas', '=', 'k.id_kelas')
            ->whereIn('j.id_kelas', $idKelasHariIni)
            ->where('j.id_tentor', $idTentor)
            ->whereColumn('a.id_siswa', 'k.id_siswa')
            ->where('a.status', 'hadir')
            ->selectRaw('j.id_kelas, count(*) as total')
            ->groupBy('j.id_kelas')
            ->pluck('total', 'id_kelas');

        $jadwalHariIni = $rows->map(function ($j) use ($totalPertemuan, $hadirPerKelas) {
            $j->total  = (int) ($totalPertemuan[$j->id_kelas] ?? 0);
            $j->hadir  = (int) ($hadirPerKelas[$j->id_kelas] ?? 0);
            $j->persen = $j->total > 0 ? round($j->hadir / $j->total * 100) : 0;
            $j->jam    = str_replace(':', '.', substr($j->jam_mulai, 0, 5))
                       . ' - ' . str_replace(':', '.', substr($j->jam_selesai, 0, 5));
            return $j;
        });

        return view('tentor.dashboard', compact(
            'totalKelas', 'totalSiswa', 'tugasMenunggu', 'jadwalHariIni'
        ));
    }
}