<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $idTentor = auth()->id(); // = id_user (primaryKey di model User)

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

        // tugas sudah dikumpulkan tapi belum ada nilainya
        // (kolom di DB bernama "stauts", enum: kumpul/terlambat/tidak_kumpul)
        $tugasMenunggu = DB::table('pengumpulan_tugas')
            ->join('tugas', 'pengumpulan_tugas.id_tugas', '=', 'tugas.id_tugas')
            ->where('tugas.id_tentor', $idTentor)
            ->whereIn('pengumpulan_tugas.stauts', ['kumpul', 'terlambat'])
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('nilai')
                  ->whereColumn('nilai.id_pengumpulan', 'pengumpulan_tugas.id_pengumpulan');
            })
            ->count();

        // jadwal hari ini
        $jadwalHariIni = DB::table('jadwal')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->where('jadwal.id_tentor', $idTentor)
            ->whereDate('jadwal.tanggal', today())
            ->where('jadwal.status', '!=', 'dibatalkan')
            ->orderBy('jadwal.jam_mulai')
            ->select(
                'kursus.nama_kursus',
                'kelas.nama_kelas',
                'kelas.id_kelas',
                'jadwal.jam_mulai',
                'jadwal.jam_selesai',
                'jadwal.status'
            )
            ->get();

        return view('tentor.dashboard', compact(
            'totalKelas', 'totalSiswa', 'tugasMenunggu', 'jadwalHariIni'
        ));
    }
}