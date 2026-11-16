<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\SiswaKursus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DataPenggunaController extends Controller
{
    // nampilin semua data
    public function index(Request $request)
    {
        // Ambil daftar kursus untuk filter siswa
        $daftarKursusSiswa = DB::table('kursus')
            ->orderBy('nama_kursus')
            ->get();

        // Hitung statistik siswa berdasarkan pendaftaran kursus
        $totalSiswa = DB::table('siswa_kursus')
            ->distinct()
            ->count('id_siswa');

        $siswaAktif = DB::table('siswa_kursus')
            ->where('status', 'aktif')
            ->distinct()
            ->count('id_siswa');

        $siswaSelesai = DB::table('siswa_kursus')
            ->where('status', 'selesai')
            ->distinct()
            ->count('id_siswa');

        $siswaKeluar = DB::table('siswa_kursus')
            ->where('status', 'keluar')
            ->distinct()
            ->count('id_siswa');

        // Ambil data siswa beserta kursus, jadwal, dan tentor
        $querySiswa = DB::table('siswa_kursus as sk')
            ->join('users as s', 'sk.id_siswa', '=', 's.id_user')
            ->join('kursus as k', 'sk.id_kursus', '=', 'k.id_kursus')
            ->leftJoin('kelas as kl', function ($join) {
                $join->on('kl.id_siswa', '=', 'sk.id_siswa')
                    ->on('kl.id_kursus', '=', 'sk.id_kursus');
            })
            ->leftJoin('jadwal as j', 'kl.id_kelas', '=', 'j.id_kelas')
            ->leftJoin('users as t', 'j.id_tentor', '=', 't.id_user')
            ->select(
                'sk.id_siswa_kursus',
                's.nama',
                'k.nama_kursus',
                'sk.status'
            )
            ->selectRaw("
                STRING_AGG(
                    DISTINCT EXTRACT(ISODOW FROM j.tanggal)::INTEGER::TEXT,
                    ',' ORDER BY EXTRACT(ISODOW FROM j.tanggal)::INTEGER::TEXT
                ) AS hari_jadwal
            ")
            ->selectRaw("
                STRING_AGG(
                    DISTINCT TO_CHAR(j.jam_mulai, 'HH24:MI'),
                    ', ' ORDER BY TO_CHAR(j.jam_mulai, 'HH24:MI')
                ) AS pukul
            ")
            ->selectRaw("STRING_AGG(DISTINCT t.nama, ', ') AS nama_tentor")
            ->groupBy(
                'sk.id_siswa_kursus',
                's.nama',
                'k.nama_kursus',
                'sk.status'
            );

        if ($request->filled('siswa_search')) {
            $querySiswa->where('s.nama', 'ILIKE', '%' . $request->siswa_search . '%');
        }

        if ($request->filled('siswa_kursus')) {
            $querySiswa->where('sk.id_kursus', $request->siswa_kursus);
        }

        if ($request->filled('siswa_status')) {
            $querySiswa->where('sk.status', $request->siswa_status);
        }

        $dataSiswa = $querySiswa
            ->orderBy('s.nama')
            ->paginate(5, ['*'], 'siswa_page')
            ->withQueryString();

        // Ubah nomor hari menjadi nama hari
        $namaHari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        $dataSiswa->getCollection()->transform(function ($item) use ($namaHari) {
            $item->jadwal = collect(explode(',', $item->hari_jadwal ?? ''))
                ->filter()
                ->map(fn ($hari) => $namaHari[(int) $hari] ?? null)
                ->filter()
                ->implode(', ');

            $item->pukul = $item->pukul ?: '-';
            $item->nama_tentor = $item->nama_tentor ?: '-';

            return $item;
        });

        // Ambil daftar keahlian tentor untuk filter
        $daftarKeahlian = DB::table('kursus')
            ->orderBy('nama_kursus')
            ->get();

        // Hitung statistik tentor
        $totalTentor = DB::table('users')
            ->where('role', 'tentor')
            ->count();

        $tentorAktif = DB::table('users')
            ->where('role', 'tentor')
            ->where('status', 'aktif')
            ->count();

        $tentorNonaktif = DB::table('users')
            ->where('role', 'tentor')
            ->where('status', 'nonaktif')
            ->count();

        $kelasBerjalan = DB::table('kelas')
            ->where('status', 'aktif')
            ->count();

        // Ambil data tentor beserta kursus dan jadwal
        $queryTentor = DB::table('tentor_kursus as tk')
            ->join('users as t', 'tk.id_tentor', '=', 't.id_user')
            ->join('kursus as k', 'tk.id_kursus', '=', 'k.id_kursus')
            ->leftJoin('kelas as kl', 'kl.id_kursus', '=', 'tk.id_kursus')
            ->leftJoin('jadwal as j', function ($join) {
                $join->on('j.id_kelas', '=', 'kl.id_kelas')
                    ->on('j.id_tentor', '=', 'tk.id_tentor');
            })
            ->select(
                'tk.id_tentor_kursus',
                't.nama',
                't.status',
                'k.nama_kursus'
            )
            ->selectRaw("
                COUNT(DISTINCT CASE
                    WHEN kl.status = 'aktif' THEN kl.id_siswa
                END) AS total_siswa
            ")
            ->selectRaw("
                STRING_AGG(
                    DISTINCT EXTRACT(ISODOW FROM j.tanggal)::INTEGER::TEXT,
                    ',' ORDER BY EXTRACT(ISODOW FROM j.tanggal)::INTEGER::TEXT
                ) AS hari_jadwal
            ")
            ->selectRaw("
                STRING_AGG(
                    DISTINCT TO_CHAR(j.jam_mulai, 'HH24:MI'),
                    ', ' ORDER BY TO_CHAR(j.jam_mulai, 'HH24:MI')
                ) AS pukul
            ")
            ->groupBy(
                'tk.id_tentor_kursus',
                't.nama',
                't.status',
                'k.nama_kursus'
            );

        if ($request->filled('tentor_search')) {
            $queryTentor->where('t.nama', 'ILIKE', '%' . $request->tentor_search . '%');
        }

        if ($request->filled('tentor_keahlian')) {
            $queryTentor->where('tk.id_kursus', $request->tentor_keahlian);
        }

        if ($request->filled('tentor_status')) {
            $queryTentor->where('t.status', $request->tentor_status);
        }

        $dataTentor = $queryTentor
            ->orderBy('t.nama')
            ->paginate(5, ['*'], 'tentor_page')
            ->withQueryString();

        $dataTentor->getCollection()->transform(function ($item) use ($namaHari) {
            $item->jadwal = collect(explode(',', $item->hari_jadwal ?? ''))
                ->filter()
                ->map(fn ($hari) => $namaHari[(int) $hari] ?? null)
                ->filter()
                ->implode(', ');

            $item->pukul = $item->pukul ?: '-';

            return $item;
        });

        // Hitung statistik operator
        $totalOperator = DB::table('users')
            ->where('role', 'operator')
            ->count();

        $operatorAktif = DB::table('users')
            ->where('role', 'operator')
            ->where('status', 'aktif')
            ->count();

        // Hitung pengajuan jadwal yang menunggu dan sudah diproses
        $pengajuanMenunggu = DB::table('pengajuan_jadwal')
            ->where('status', 'menunggu')
            ->count();

        $pengajuanDiproses = DB::table('pengajuan_jadwal')
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->count();

        // Ambil data operator
        $queryOperator = DB::table('users')
            ->where('role', 'operator')
            ->select(
                'id_user',
                'nama',
                'email',
                'no_telp',
                'tahun_masuk',
                'status'
            );

        if ($request->filled('operator_search')) {
            $queryOperator->where('nama', 'ILIKE', '%' . $request->operator_search . '%');
        }

        if ($request->filled('operator_status')) {
            $queryOperator->where('status', $request->operator_status);
        }

        $dataOperator = $queryOperator
            ->orderBy('nama')
            ->paginate(5, ['*'], 'operator_page')
            ->withQueryString();

        return view('admin.dataPengguna', compact(
            'dataSiswa',
            'dataTentor',
            'dataOperator',
            'daftarKursusSiswa',
            'daftarKeahlian',
            'totalSiswa',
            'siswaAktif',
            'siswaSelesai',
            'siswaKeluar',
            'totalTentor',
            'tentorAktif',
            'tentorNonaktif',
            'kelasBerjalan',
            'totalOperator',
            'operatorAktif',
            'pengajuanMenunggu',
            'pengajuanDiproses'
        ));
    }
}
