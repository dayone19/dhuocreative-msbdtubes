<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Kursus;
use App\Models\TentorKursus;
use App\Models\SiswaKursus;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // nampilin semua 
    public function index() {
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalTentor = User::where('role', 'tentor')->count();
        $totalOperator = User::where('role', 'operator')->count();

        $totalKursusAktif = DB::table('siswa_kursus')
            ->where('status', 'aktif')
            ->count();

        $totalJadwalHariIni = DB::table('jadwal')
            ->whereDate('tanggal', today())
            ->where('status', 'terjadwal')
            ->count();

        $statistikKursus = DB::table('kursus as k')
            ->leftJoin('siswa_kursus as sk', 'k.id_kursus', '=', 'sk.id_kursus')
            ->leftJoin('tentor_kursus as tk', 'k.id_kursus', '=', 'tk.id_kursus')
            ->select(
                'k.id_kursus',
                'k.nama_kursus',
                DB::raw("COUNT(DISTINCT CASE WHEN sk.status = 'aktif' THEN sk.id_siswa END) AS total_siswa"),
                DB::raw('COUNT(DISTINCT tk.id_tentor) AS total_tentor')
            )
            ->groupBy('k.id_kursus', 'k.nama_kursus')
            ->get();

        $pertumbuhanSiswa = DB::table('siswa_kursus as sk')
            ->join('kursus as k', 'sk.id_kursus', '=', 'k.id_kursus')
            ->select(
                'k.id_kursus',
                'k.nama_kursus',
                DB::raw('COUNT(CASE WHEN EXTRACT(MONTH FROM sk.tanggal_daftar) = EXTRACT(MONTH FROM CURRENT_DATE) AND EXTRACT(YEAR FROM sk.tanggal_daftar) = EXTRACT(YEAR FROM CURRENT_DATE) THEN 1 END) as sekarang'),
                DB::raw('COUNT(CASE WHEN sk.tanggal_daftar >= DATE_TRUNC(\'month\', CURRENT_DATE - INTERVAL \'1 month\') AND sk.tanggal_daftar < DATE_TRUNC(\'month\', CURRENT_DATE) THEN 1 END) as lalu')
            )
            ->groupBy('k.id_kursus', 'k.nama_kursus')
            ->get();

        $bulan = request('bulan', now()->month);
        $tahun = request('tahun', now()->year);

        $periodeAwal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $periodeAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $laporanAbsensi = DB::table('absensi_siswa as a')
            ->join('jadwal as j', 'a.id_jadwal', '=', 'j.id_jadwal')
            ->join('kelas as k', 'j.id_kelas', '=', 'k.id_kelas')
            ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
            ->join('users as s', 'a.id_siswa', '=', 's.id_user')
            ->join('users as t', 'j.id_tentor', '=', 't.id_user')
            ->whereBetween('j.tanggal', [$periodeAwal, $periodeAkhir])
            ->where('j.status', 'selesai')
            ->select(
                's.id_user',
                's.nama as nama_siswa',
                'ku.nama_kursus',
                DB::raw("INITCAP(k.kelas::text) as jenis_kelas"),
                DB::raw("STRING_AGG(DISTINCT t.nama, ', ') as nama_tentor"),
                DB::raw('COUNT(DISTINCT j.id_jadwal) as total_pertemuan'),
                DB::raw("COUNT(a.id_absensi) FILTER (WHERE a.status = 'hadir') as hadir"),
                DB::raw("COUNT(a.id_absensi) FILTER (WHERE a.status = 'izin') as izin"),
                DB::raw("COUNT(a.id_absensi) FILTER (WHERE a.status = 'sakit') as sakit"),
                DB::raw("COUNT(a.id_absensi) FILTER (WHERE a.status = 'alpha') as alpa"),
                DB::raw("
                    ROUND(
                        COUNT(a.id_absensi) FILTER (WHERE a.status = 'hadir') * 100.0
                        / NULLIF(COUNT(DISTINCT j.id_jadwal), 0),
                        1
                    ) as persentase_kehadiran
                ")
            )
            ->groupBy(
                's.id_user',
                's.nama',
                'ku.nama_kursus',
                'k.kelas'
            )
            ->orderBy('s.nama')
            ->orderBy('ku.nama_kursus')
            ->get();

        $laporanSiswa = DB::table('kursus as k')
            ->leftJoin('siswa_kursus as sk', 'k.id_kursus', '=', 'sk.id_kursus')
            ->leftJoin('users as u', 'sk.id_siswa', '=', 'u.id_user')
            ->select(
                'k.id_kursus',
                'k.nama_kursus',
                'k.status as status_kursus',
                DB::raw("
                    COUNT(DISTINCT CASE
                        WHEN sk.status = 'aktif' THEN sk.id_siswa
                    END) as total_siswa_aktif
                "),
                DB::raw("
                    COUNT(DISTINCT CASE
                        WHEN sk.status = 'aktif'
                        AND u.created_at >= '{$periodeAwal->toDateString()}'
                        AND u.created_at < '{$periodeAkhir->copy()->addDay()->toDateString()}'
                        THEN sk.id_siswa
                    END) as siswa_baru
                ")
            )
            ->groupBy('k.id_kursus', 'k.nama_kursus', 'k.status')
            ->orderBy('k.nama_kursus')
            ->get();

        $maxJumlahKursus = max(
            (int) $statistikKursus->max('total_siswa'),
            (int) $statistikKursus->max('total_tentor')
        );

        $rangeY = min(5, max(1, $maxJumlahKursus));
        $intervalY = (int) ceil($maxJumlahKursus / $rangeY);
        $maxY = $intervalY * $rangeY;

        $totalPertambahanSiswa = 0;

        foreach ($pertumbuhanSiswa as $item) {
            $totalPertambahanSiswa += max(0, $item->sekarang - $item->lalu);
        }

        $warnaDonut = ['#f27640', '#ffb83f', '#286c65', '#ffdc59', '#8aaea9'];

        $distribusiKursus = [];
        $donutGradient = [];
        $akumulasiPersen = 0;

        foreach ($pertumbuhanSiswa as $idx => $item) {
            $selisih = max(0, $item->sekarang - $item->lalu);

            $persen = $totalPertambahanSiswa > 0
                ? ($selisih / $totalPertambahanSiswa) * 100
                : 0;

            $warna = $warnaDonut[$idx % count($warnaDonut)];

            $distribusiKursus[] = [
                'nama' => $item->nama_kursus,
                'jumlah' => $selisih,
                'persen' => round($persen, 1),
                'warna' => $warna
            ];

            $batasAkhir = $akumulasiPersen + $persen;

            $donutGradient[] = "{$warna} {$akumulasiPersen}% {$batasAkhir}%";

            $akumulasiPersen = $batasAkhir;
        }

        $donutBackground = count($donutGradient) > 0
            ? 'conic-gradient(' . implode(', ', $donutGradient) . ')'
            : '#e4e8e4';    

        return view('Admin.dashboard', compact(
            'totalSiswa',
            'totalTentor',
            'totalOperator',
            'totalKursusAktif',
            'totalJadwalHariIni',
            'statistikKursus',
            'pertumbuhanSiswa',
            'bulan',
            'tahun',
            'periodeAwal',
            'periodeAkhir',
            'laporanAbsensi',
            'laporanSiswa',
            'maxY',
            'intervalY',
            'rangeY',
            'totalPertambahanSiswa',
            'distribusiKursus',
            'donutBackground'
        ));
    }
}
