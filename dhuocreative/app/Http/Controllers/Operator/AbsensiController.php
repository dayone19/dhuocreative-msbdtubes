<?php
// Controller Rekap Absensi Operator - Dhuo Creative

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Jadwal;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');

        $query = DB::table('kelas as k')
            ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
            ->join('users as s', 'k.id_siswa', '=', 's.id_user')
            ->leftJoin('jadwal as j', 'k.id_kelas', '=', 'j.id_kelas')
            ->leftJoin('users as t', 'j.id_tentor', '=', 't.id_user')
            ->leftJoin('absensi_siswa as a', function ($join) {
                $join->on('j.id_jadwal', '=', 'a.id_jadwal')
                     ->on('k.id_siswa', '=', 'a.id_siswa');
            })
            ->where('s.role', 'siswa')
            ->select(
                'k.id_kelas',
                'k.id_siswa',
                's.nama as nama_siswa',
                'ku.nama_kursus',
                'k.kelas as nama_kelas',
                DB::raw("COALESCE(MAX(t.nama), 'Belum Ditentukan') as nama_tentor"),
                DB::raw('COUNT(DISTINCT j.id_jadwal) as total_pertemuan'),
                DB::raw("COUNT(DISTINCT CASE WHEN a.status = 'hadir' THEN a.id_absensi END) as jumlah_hadir"),
                DB::raw("COUNT(DISTINCT CASE WHEN a.status IN ('izin', 'sakit') THEN a.id_absensi END) as jumlah_izin"),
                DB::raw("COUNT(DISTINCT CASE WHEN a.status = 'alpha' THEN a.id_absensi END) as jumlah_alpa")
            )
            ->groupBy('k.id_kelas', 'k.id_siswa', 's.nama', 'ku.nama_kursus', 'k.kelas')
            ->orderBy('k.id_kelas', 'desc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('s.nama', 'ilike', "%{$search}%")
                  ->orWhere('ku.nama_kursus', 'ilike', "%{$search}%")
                  ->orWhere('t.nama', 'ilike', "%{$search}%")
                  ->orWhere('k.kelas', 'ilike', "%{$search}%");
            });
        }

        $rekapRaw = $query->get();

        $rekapAbsensi = [];
        $totalHadirSemua = 0;
        $totalPertemuanSemua = 0;

        foreach ($rekapRaw as $row) {
            $totalPertemuanSemua += $row->total_pertemuan;
            $totalHadirSemua += $row->jumlah_hadir;

            $rekapAbsensi[] = [
                'id'         => $row->id_kelas,
                'id_siswa'   => $row->id_siswa,
                'kursus'     => $row->nama_kursus,
                'nama'       => $row->nama_siswa,
                'tentor'     => $row->nama_tentor,
                'kelas'      => $row->nama_kelas,
                'hadir'      => (int) $row->jumlah_hadir,
                'total'      => (int) $row->total_pertemuan,
                'izin'       => (int) $row->jumlah_izin,
                'alpa'       => (int) $row->jumlah_alpa,
                'persentase' => $row->total_pertemuan > 0 ? round(($row->jumlah_hadir / $row->total_pertemuan) * 100) : 0,
            ];
        }

        $totalSiswa = count(array_unique(array_column($rekapAbsensi, 'id_siswa')));
        $rataRataHadir = $totalPertemuanSemua > 0 ? round(($totalHadirSemua / $totalPertemuanSemua) * 100) : 0;

        $stats = [
            'total_siswa'     => $totalSiswa,
            'total_kehadiran' => $totalHadirSemua,
            'total_pertemuan' => $totalPertemuanSemua,
            'rata_rata_hadir' => $rataRataHadir,
        ];

        return view('operator.absensi', compact('rekapAbsensi', 'stats'));
    }

    public function detail(Request $request, $id = null)
    {
        $id = $id ?? $request->query('id');

        $kelas = null;
        if ($id) {
            $kelas = Kelas::with(['kursus', 'siswa'])->find($id);

            if (!$kelas) {
                $kelas = Kelas::with(['kursus', 'siswa'])->where('id_siswa', $id)->first();
            }
        }

        if (!$kelas) {
            $kelas = Kelas::with(['kursus', 'siswa'])->where('status', 'aktif')->orderBy('id_kelas', 'desc')->first();
        }

        if (!$kelas || !$kelas->siswa) {
            return redirect()->route('operator.absensi')->with('error', 'Data kelas siswa tidak ditemukan.');
        }

        $siswaKursus = DB::table('siswa_kursus')
            ->where('id_siswa', $kelas->id_siswa)
            ->where('id_kursus', $kelas->id_kursus)
            ->first();

        $tentorNama = DB::table('jadwal as j')
            ->join('users as t', 'j.id_tentor', '=', 't.id_user')
            ->where('j.id_kelas', $kelas->id_kelas)
            ->value('t.nama') ?? 'Tentor Pembimbing';

        $jadwalList = DB::table('jadwal as j')
            ->leftJoin('absensi_siswa as a', function ($join) use ($kelas) {
                $join->on('j.id_jadwal', '=', 'a.id_jadwal')
                     ->where('a.id_siswa', '=', $kelas->id_siswa);
            })
            ->where('j.id_kelas', $kelas->id_kelas)
            ->orderBy('j.tanggal', 'asc')
            ->orderBy('j.jam_mulai', 'asc')
            ->select(
                'j.id_jadwal',
                'j.tanggal',
                'j.jam_mulai',
                'j.jam_selesai',
                'j.status as status_jadwal',
                'a.status as status_absen',
                'a.waktu_absen'
            )
            ->get();

        $daftarPertemuan = [];
        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];

        foreach ($jadwalList as $idx => $j) {
            $carbonDate = Carbon::parse($j->tanggal);
            $dayEnglish = $carbonDate->format('l');
            $hariIndo   = $hariMap[$dayEnglish] ?? $dayEnglish;
            $jamMulai   = substr($j->jam_mulai, 0, 5);
            $jamSelesai = substr($j->jam_selesai, 0, 5);

            $status = $j->status_absen;
            if (!$status) {
                $status = $carbonDate->isPast() ? 'alpha' : 'belum hadir';
            }

            if ($status === 'alpha') {
                $status = 'alpa';
            }

            $daftarPertemuan[] = [
                'ke'      => $idx + 1,
                'tanggal' => $j->tanggal,
                'hari'    => $hariIndo,
                'jam'     => "{$jamMulai} – {$jamSelesai}",
                'status'  => $status,
                'jadwal'  => 'Jadwal tetap',
            ];
        }

        $totalPertemuan = count($daftarPertemuan);
        $totalHadir = collect($daftarPertemuan)->where('status', 'hadir')->count();
        $totalTercatat = collect($daftarPertemuan)->whereIn('status', ['hadir', 'izin', 'sakit', 'alpa'])->count();
        $sisaPertemuan = max(0, $totalPertemuan - $totalTercatat);
        $persentaseHadir = $totalPertemuan > 0 ? round(($totalHadir / $totalPertemuan) * 100) : 0;
        $persentaseSisa = $totalPertemuan > 0 ? round(($sisaPertemuan / $totalPertemuan) * 100) : 0;

        $mulaiKontrak = $siswaKursus->tanggal_daftar ?? ($jadwalList->first()->tanggal ?? date('Y-m-d'));
        $akhirKontrak = $jadwalList->last()->tanggal ?? Carbon::parse($mulaiKontrak)->addMonths(3)->format('Y-m-d');

        $siswa = [
            'id'            => $kelas->siswa->id_user,
            'id_kelas'      => $kelas->id_kelas,
            'nama'          => $kelas->siswa->nama,
            'kursus'        => $kelas->kursus->nama_kursus ?? 'Kursus',
            'kelas'         => $kelas->kelas,
            'tentor'        => $tentorNama,
            'mulai_kontrak' => $mulaiKontrak,
            'akhir_kontrak' => $akhirKontrak,
        ];

        return view('operator.absensiSiswa', compact(
            'siswa',
            'daftarPertemuan',
            'totalPertemuan',
            'totalHadir',
            'totalTercatat',
            'sisaPertemuan',
            'persentaseHadir',
            'persentaseSisa'
        ));
    }
}
