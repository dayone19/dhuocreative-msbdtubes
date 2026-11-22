<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiwayatAbsensiController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil daftar kelas dan kursus
        $daftarKelas = DB::table('kelas as k')
            ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
            ->select('ku.id_kursus', 'ku.nama_kursus')
            ->distinct()
            ->orderBy('ku.nama_kursus')
            ->get();
        
        // Mengambil rekap absensi siswa dari jadwal yang sudah lewat
        $tanggalHariIni = Carbon::today()->toDateString();
        $jamSekarang = Carbon::now()->format('H:i:s');

        $query = DB::table('kelas as k')
            ->join('users as u', 'k.id_siswa', '=', 'u.id_user')
            ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
            ->join('jadwal as j', 'k.id_kelas', '=', 'j.id_kelas')
            ->leftJoin('absensi_siswa as a', function ($join) {
                $join->on('j.id_jadwal', '=', 'a.id_jadwal')
                    ->on('k.id_siswa', '=', 'a.id_siswa');
            })
            ->where('j.status', '<>', 'dibatalkan')
            ->where(function ($where) use ($tanggalHariIni, $jamSekarang) {
                $where->where('j.tanggal', '<', $tanggalHariIni)
                    ->orWhere(function ($sub) use ($tanggalHariIni, $jamSekarang) {
                        $sub->where('j.tanggal', '=', $tanggalHariIni)
                            ->where('j.jam_selesai', '<', $jamSekarang);
                    });
            })
            ->select(
                'k.id_kelas',
                'u.id_user as id_siswa',
                'u.nama as nama_siswa',
                'ku.nama_kursus',
                'k.kelas'
            )
            ->selectRaw('COUNT(DISTINCT j.id_jadwal) as total_pertemuan')
            ->selectRaw("
                COUNT(DISTINCT CASE
                    WHEN a.status = 'hadir' THEN a.id_absensi
                END) as total_hadir
            ")
            ->groupBy(
                'k.id_kelas',
                'u.id_user',
                'u.nama',
                'ku.nama_kursus',
                'k.kelas'
            );
        // Filter berdasarkan kelas
        if ($request->filled('id_kursus')) {
            $query->where('k.id_kursus', $request->id_kursus);
        }

        $daftarSiswa = $query
            ->orderBy('u.nama')
            ->paginate(10)
            ->withQueryString();

        // Menghitung persentase kehadiran
        $daftarSiswa->getCollection()->transform(function ($siswa) {
            $siswa->persentase = $siswa->total_pertemuan > 0
                ? round(($siswa->total_hadir / $siswa->total_pertemuan) * 100)
                : 0;

            return $siswa;
        });

        return view('admin.riwayatAbsen', compact(
            'daftarKelas',
            'daftarSiswa'
        ));
    }

    public function detail($id_kelas, $id_siswa)
    {
        // Mengambil data siswa, kelas, dan kursus
        $kelas = DB::table('kelas as k')
            ->join('users as u', 'k.id_siswa', '=', 'u.id_user')
            ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
            ->where('k.id_kelas', $id_kelas)
            ->where('u.id_user', $id_siswa)
            ->select(
                'k.id_kelas',
                'u.id_user',
                'u.nama',
                'k.kelas',
                'ku.nama_kursus',
                'ku.jumlah_pertemuan'
            )
            ->first();

        abort_if(!$kelas, 404);

        // Mengambil tanggal jadwal pertama siswa
        $jadwalPertama = DB::table('jadwal')
            ->where('id_kelas', $id_kelas)
            ->where('status', '<>', 'dibatalkan')
            ->min('tanggal');

        // Menghitung masa kontrak 15 pertemuan, seminggu sekali
        $mulaiKontrak = $jadwalPertama
            ? Carbon::parse($jadwalPertama)
            : null;

        $akhirKontrak = $mulaiKontrak
            ? $mulaiKontrak->copy()->addWeeks(14)
            : null;

        // Data siswa untuk Blade
        $siswa = [
            'id' => $kelas->id_user,
            'id_kelas' => $kelas->id_kelas,
            'nama' => $kelas->nama,
            'kursus' => $kelas->nama_kursus,
            'kelas' => $kelas->kelas,
            'mulai_kontrak' => $mulaiKontrak?->format('Y-m-d'),
            'akhir_kontrak' => $akhirKontrak?->format('Y-m-d'),
        ];

        // Mengambil riwayat jadwal yang sudah selesai
        $tanggalHariIni = Carbon::today()->toDateString();
        $jamSekarang = Carbon::now()->format('H:i:s');

        $jadwal = DB::table('jadwal as j')
            ->leftJoin('absensi_siswa as a', function ($join) use ($id_siswa) {
                $join->on('j.id_jadwal', '=', 'a.id_jadwal')
                    ->where('a.id_siswa', '=', $id_siswa);
            })
            ->where('j.id_kelas', $id_kelas)
            ->where('j.status', '<>', 'dibatalkan')
            ->where(function ($where) use ($tanggalHariIni, $jamSekarang) {
                $where->where('j.tanggal', '<', $tanggalHariIni)
                    ->orWhere(function ($sub) use ($tanggalHariIni, $jamSekarang) {
                        $sub->where('j.tanggal', '=', $tanggalHariIni)
                            ->where('j.jam_selesai', '<', $jamSekarang);
                    });
            })
            ->select(
                'j.id_jadwal',
                'j.pertemuan_ke',
                'j.tanggal',
                'j.jam_mulai',
                'j.jam_selesai',
                'a.status as status_absensi'
            )
            ->orderBy('j.pertemuan_ke')
            ->get();


        // Menyesuaikan data dengan kebutuhan Blade
        $daftarPertemuan = $jadwal->map(function ($item, $index) {
            $tanggal = Carbon::parse($item->tanggal);

            return [
                'ke' => $item->pertemuan_ke ?? ($index + 1),
                'tanggal' => $tanggal->format('Y-m-d'),
                'hari' => $tanggal->locale('id')->translatedFormat('l'),
                'jam' => substr($item->jam_mulai, 0, 5)
                    . ' - '
                    . substr($item->jam_selesai, 0, 5),
                'id_jadwal' => $item->id_jadwal,
                'jadwal' => 'Jadwal kelas',
                'status' => $item->status_absensi ?? 'belum absen',
            ];
        });

        // Menghitung ringkasan absensi
        $totalPertemuan = (int) ($kelas->jumlah_pertemuan ?: 15);
        $jumlahJadwal = $daftarPertemuan->count();

        $totalHadir = $daftarPertemuan
            ->where('status', 'hadir')
            ->count();

        $sisaPertemuan = max(0, $totalPertemuan - $jumlahJadwal);

        $persentaseHadir = $jumlahJadwal > 0
            ? round(($totalHadir / $jumlahJadwal) * 100)
            : 0;

        return view('admin.riwayatPresensi', compact(
            'siswa',
            'daftarPertemuan',
            'totalPertemuan',
            'jumlahJadwal',
            'totalHadir',
            'sisaPertemuan',
            'persentaseHadir'
        ));
    }

    // edit status kehadiran
    public function updateStatus(Request $request, $id_kelas, $id_siswa, $id_jadwal)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        $tanggalHariIni = Carbon::today()->toDateString();
        $jamSekarang = Carbon::now()->format('H:i:s');

        // Memastikan jadwal benar-benar kelas dan sudah lewat
        $jadwal = DB::table('jadwal')
            ->where('id_jadwal', $id_jadwal)
            ->where('id_kelas', $id_kelas)
            ->where('status', '<>', 'dibatalkan')
            ->where(function ($where) use ($tanggalHariIni, $jamSekarang) {
                $where->where('tanggal', '<', $tanggalHariIni)
                    ->orWhere(function ($sub) use ($tanggalHariIni, $jamSekarang) {
                        $sub->where('tanggal', '=', $tanggalHariIni)
                            ->where('jam_selesai', '<', $jamSekarang);
                    });
            })
            ->first();

        abort_if(!$jadwal, 404);

        // Memastikan siswa memang terdaftar di kelas tersebut
        $siswa = DB::table('kelas')
            ->where('id_kelas', $id_kelas)
            ->where('id_siswa', $id_siswa)
            ->exists();

        abort_if(!$siswa, 404);

        // Memeriksa apakah absensi siswa sudah tersedia
        $absensi = DB::table('absensi_siswa')
            ->where('id_jadwal', $id_jadwal)
            ->where('id_siswa', $id_siswa)
            ->first();

        if ($absensi) {
            // Memperbarui status absensi yang sudah ada
            DB::table('absensi_siswa')
                ->where('id_absensi', $absensi->id_absensi)
                ->update([
                    'status' => $request->status,
                ]);
        } else {
            // Membuat absensi jika sebelumnya belum tercatat pakai store procedure
            DB::statement(
                'CALL catat_absensi_siswa(?, ?, ?)',
                [
                    $id_jadwal,
                    $id_siswa,
                    $request->status,
                ]
            );
        }

        return redirect()->back()->with(
            'success',
            'Status absensi berhasil diperbarui.'
        );
    }

}
