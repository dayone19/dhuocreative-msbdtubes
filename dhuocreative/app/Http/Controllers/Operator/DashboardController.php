<?php
// Controller Dashboard Operator - Dhuo Creative

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Kursus;
use App\Models\Jadwal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');

        $totalJadwalHariIni = Jadwal::where('tanggal', $today)->count();
        $totalSiswaAktif    = User::where('role', 'siswa')->where('status', 'aktif')->count();
        $totalKelasAktif    = Kelas::where('status', 'aktif')->count();
        $totalTentor        = User::where('role', 'tentor')->count();

        $ringkasan = [
            [
                'judul'   => 'Jadwal Hari Ini',
                'angka'   => $totalJadwalHariIni,
                'satuan'  => 'Kelas',
                'lebar'   => 195,
                'arrow'   => true,
                'url'     => '#sectionJadwalHariIni'
            ],
            [
                'judul'   => 'Total Siswa Aktif',
                'angka'   => $totalSiswaAktif,
                'satuan'  => 'Siswa',
                'lebar'   => 205,
                'arrow'   => true,
                'url'     => route('operator.siswa')
            ],
            [
                'judul'   => 'Total Kelas Kursus Aktif',
                'angka'   => $totalKelasAktif,
                'satuan'  => 'Kelas',
                'lebar'   => 195,
                'arrow'   => false,
                'url'     => '#'
            ],
            [
                'judul'   => 'Total Tentor',
                'angka'   => $totalTentor,
                'satuan'  => 'Tentor',
                'lebar'   => 195,
                'arrow'   => false,
                'url'     => '#'
            ],
        ];

        $jadwalHariIniRaw = Jadwal::with(['kelas.kursus', 'kelas.siswa', 'tentor'])
            ->where('tanggal', $today)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $jadwalHariIni = [];
        foreach ($jadwalHariIniRaw as $j) {
            $absensi = DB::table('absensi_siswa')->where('id_jadwal', $j->id_jadwal)->first();
            $isHadir = $absensi && $absensi->status === 'hadir' ? 1 : 0;
            $jamStr = substr($j->jam_mulai, 0, 5) . ' – ' . substr($j->jam_selesai, 0, 5);

            $jadwalHariIni[] = [
                'kursus' => $j->kelas->kursus->nama_kursus ?? 'Kursus',
                'siswa'  => $j->kelas->siswa->nama ?? 'Siswa',
                'tentor' => $j->tentor->nama ?? 'Tentor',
                'jam'    => $jamStr,
                'hadir'  => $isHadir,
                'total'  => 1,
            ];
        }

        return view('operator.dashboard', compact('ringkasan', 'jadwalHariIni'));
    }

    public function downloadLaporan(Request $request, $jenis)
    {
        $bulanParam = $request->query('bulan', date('Y-m'));
        $start = Carbon::parse($bulanParam . '-01')->startOfMonth();
        $end   = Carbon::parse($bulanParam . '-01')->endOfMonth();

        if ($jenis === 'absensi') {
            $data = DB::table('absensi_siswa as a')
                ->join('jadwal as j', 'a.id_jadwal', '=', 'j.id_jadwal')
                ->join('kelas as k', 'j.id_kelas', '=', 'k.id_kelas')
                ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
                ->join('users as s', 'a.id_siswa', '=', 's.id_user')
                ->join('users as t', 'j.id_tentor', '=', 't.id_user')
                ->whereBetween('j.tanggal', [$start, $end])
                ->select(
                    's.nama as nama_siswa',
                    'ku.nama_kursus',
                    'k.kelas as jenis_kelas',
                    't.nama as nama_tentor',
                    DB::raw('COUNT(a.id_absensi) as total_sesi'),
                    DB::raw("COUNT(CASE WHEN a.status = 'hadir' THEN 1 END) as hadir"),
                    DB::raw("COUNT(CASE WHEN a.status IN ('izin', 'sakit') THEN 1 END) as izin"),
                    DB::raw("COUNT(CASE WHEN a.status = 'alpha' THEN 1 END) as alpa")
                )
                ->groupBy('s.nama', 'ku.nama_kursus', 'k.kelas', 't.nama')
                ->orderBy('s.nama', 'asc')
                ->get();

            $filename = "Laporan_Absensi_Privat_{$bulanParam}.csv";
            $csv = "\xEF\xBB\xBF"; 
            $csv .= "LAPORAN REKAP BULANAN ABSENSI\n";
            $csv .= "Bulan: {$bulanParam}\n\n";
            $csv .= "No,Nama Siswa,Kursus,Jenis Kelas,Tentor,Total Sesi Bulanan,Hadir,Izin/Sakit,Alpa,Persentase Kehadiran\n";

            $no = 1;
            foreach ($data as $row) {
                $pct = $row->total_sesi > 0 ? round(($row->hadir / $row->total_sesi) * 100, 1) : 0;
                $csv .= "{$no},\"{$row->nama_siswa}\",\"{$row->nama_kursus}\",\"{$row->jenis_kelas}\",\"{$row->nama_tentor}\",{$row->total_sesi},{$row->hadir},{$row->izin},{$row->alpa},\"{$pct}%\"\n";
                $no++;
            }

            return response($csv, 200, [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        } else {
            $data = DB::table('kursus as ku')
                ->leftJoin('kelas as k', 'ku.id_kursus', '=', 'k.id_kursus')
                ->select(
                    'ku.nama_kursus',
                    DB::raw('COUNT(DISTINCT k.id_siswa) as total_siswa'),
                    DB::raw('COUNT(DISTINCT k.id_kelas) as total_kelas')
                )
                ->groupBy('ku.id_kursus', 'ku.nama_kursus')
                ->orderBy('total_siswa', 'desc')
                ->get();

            $filename = "Laporan_Distribusi_Siswa_Kursus_{$bulanParam}.csv";
            $csv = "\xEF\xBB\xBF";
            $csv .= "LAPORAN REKAP DISTRIBUSI SISWA & KURSUS\n";
            $csv .= "Bulan: {$bulanParam}\n\n";
            $csv .= "No,Nama Kursus,Total Siswa,Total Kelas,Status\n";

            $no = 1;
            foreach ($data as $row) {
                $csv .= "{$no},\"{$row->nama_kursus}\",{$row->total_siswa} Siswa,{$row->total_kelas} Kelas,Aktif\n";
                $no++;
            }

            return response($csv, 200, [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        }
    }

    public function getLaporanData(Request $request)
    {
        $jenis = $request->query('jenis', 'absensi');
        $bulanParam = $request->query('bulan', date('Y-m'));
        $start = Carbon::parse($bulanParam . '-01')->startOfMonth();
        $end   = Carbon::parse($bulanParam . '-01')->endOfMonth();

        if ($jenis === 'absensi') {
            $data = DB::table('absensi_siswa as a')
                ->join('jadwal as j', 'a.id_jadwal', '=', 'j.id_jadwal')
                ->join('kelas as k', 'j.id_kelas', '=', 'k.id_kelas')
                ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
                ->join('users as s', 'a.id_siswa', '=', 's.id_user')
                ->join('users as t', 'j.id_tentor', '=', 't.id_user')
                ->whereBetween('j.tanggal', [$start, $end])
                ->select(
                    's.nama as nama_siswa',
                    'ku.nama_kursus',
                    'k.kelas as jenis_kelas',
                    't.nama as nama_tentor',
                    DB::raw('COUNT(a.id_absensi) as total_sesi'),
                    DB::raw("COUNT(CASE WHEN a.status = 'hadir' THEN 1 END) as hadir"),
                    DB::raw("COUNT(CASE WHEN a.status IN ('izin', 'sakit') THEN 1 END) as izin"),
                    DB::raw("COUNT(CASE WHEN a.status = 'alpha' THEN 1 END) as alpa")
                )
                ->groupBy('s.nama', 'ku.nama_kursus', 'k.kelas', 't.nama')
                ->orderBy('s.nama', 'asc')
                ->get()
                ->map(function ($row) {
                    $pct = $row->total_sesi > 0 ? round(($row->hadir / $row->total_sesi) * 100, 1) : 0;
                    $row->kehadiran_pct = $pct . '%';
                    return $row;
                });

            return response()->json($data);
        } else {
            $data = DB::table('kursus as ku')
                ->leftJoin('kelas as k', 'ku.id_kursus', '=', 'k.id_kursus')
                ->select(
                    'ku.nama_kursus',
                    DB::raw('COUNT(DISTINCT k.id_siswa) as total_siswa'),
                    DB::raw('COUNT(DISTINCT k.id_kelas) as total_kelas')
                )
                ->groupBy('ku.id_kursus', 'ku.nama_kursus')
                ->orderBy('total_siswa', 'desc')
                ->get();

            return response()->json($data);
        }
    }
}
