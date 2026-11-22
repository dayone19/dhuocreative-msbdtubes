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

        // pakai view
        $laporanAbsensi = DB::table('vw_rekap_absensi_bulanan')
            ->where('periode', $periodeAwal->toDateString())
            ->select(
                'id_siswa',
                'nama_siswa',
                DB::raw('kursus as nama_kursus'),
                'jenis_kelas',
                DB::raw("STRING_AGG(DISTINCT nama_tentor, ', ') as nama_tentor"),
                DB::raw('SUM(total_pertemuan) as total_pertemuan'),
                DB::raw('SUM(hadir) as hadir'),
                DB::raw('SUM(izin) as izin'),
                DB::raw('SUM(sakit) as sakit'),
                DB::raw('SUM(alpha) as alpa'),
                DB::raw("
                    ROUND(
                        SUM(hadir) * 100.0 / NULLIF(SUM(total_pertemuan), 0),
                        1
                    ) as persentase_kehadiran
                ")
            )
            ->groupBy(
                'id_siswa',
                'nama_siswa',
                'kursus',
                'jenis_kelas'
            )
            ->orderBy('nama_siswa')
            ->orderBy('kursus')
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
