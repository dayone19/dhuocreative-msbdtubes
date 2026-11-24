<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $idTentor = auth()->id();

        $namaTentor = DB::table('users')
            ->where('id_user', $idTentor)
            ->value('nama');

        // Daftar seluruh jadwal tentor
        $jadwal = DB::table('vw_jadwal_tentor')
            ->where('id_tentor', $idTentor)
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_mulai')
            ->select(
                'id_jadwal',
                'id_kelas',
                'kelas as kursus',
                'jenis_kelas as kelas',
                'siswa',
                DB::raw("to_char(tanggal, 'YYYY-MM-DD') as tanggal"),
                DB::raw("to_char(jam_mulai, 'HH24:MI') as mulai"),
                DB::raw("to_char(jam_selesai, 'HH24:MI') as selesai"),
                DB::raw('status::text as status')
            )
            ->get()
            ->map(function ($j) use ($namaTentor) {
                $j->tentor = $namaTentor;
                return $j;
            });

        // Navigasi kalender mingguan
        $minggu = $request->validate([
            'minggu' => ['nullable', 'date_format:Y-m-d']
        ])['minggu'] ?? null;

        $awalMinggu = $minggu
            ? Carbon::parse($minggu, 'Asia/Jakarta')->startOfWeek(Carbon::MONDAY)
            : Carbon::now('Asia/Jakarta')->startOfWeek(Carbon::MONDAY);

        $akhirMinggu = $awalMinggu->copy()->addDays(5);

        $prevWeek = $awalMinggu->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $awalMinggu->copy()->addWeek()->format('Y-m-d');

        $labelMinggu = $awalMinggu->translatedFormat('d M Y')
            . ' – ' .
            $akhirMinggu->translatedFormat('d M Y');

        // Jadwal untuk kalender mingguan
        $jadwalKalender = DB::table('vw_jadwal_tentor')
            ->where('id_tentor', $idTentor)
            ->whereBetween('tanggal', [
                $awalMinggu->toDateString(),
                $akhirMinggu->toDateString()
            ])
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->select(
                'id_jadwal',
                'id_kelas',
                'siswa',
                'kelas as kursus',
                'jenis_kelas as kelas',
                DB::raw("to_char(tanggal, 'YYYY-MM-DD') as tanggal"),
                DB::raw("to_char(jam_mulai, 'HH24:MI') as mulai"),
                DB::raw("to_char(jam_selesai, 'HH24:MI') as selesai"),
                DB::raw('status::text as status')
            )
            ->get()
            ->map(function ($j) use ($namaTentor) {
                $j->tentor = $namaTentor;
                return $j;
            });

        // Mengelompokkan jadwal berdasarkan hari
        $hariList = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu'
        ];

        $jadwalMingguan = [];

        foreach ($hariList as $isoDay => $namaHari) {
            $tanggalHari = $awalMinggu
                ->copy()
                ->addDays($isoDay - 1)
                ->format('Y-m-d');

            $jadwalHari = $jadwalKalender
                ->filter(function ($j) use ($tanggalHari) {
                    return $j->tanggal === $tanggalHari
                        && $j->status === 'terjadwal';
                })
                ->values();

            $jadwalMingguan[$namaHari] = [
                'tanggal' => $awalMinggu
                    ->copy()
                    ->addDays($isoDay - 1)
                    ->format('d M'),
                'jadwals' => $jadwalHari
            ];
        }

        // Riwayat perubahan jadwal
        $riwayatPerubahan = DB::table('pengajuan_jadwal as p')
            ->join('kelas', 'p.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as siswa', 'kelas.id_siswa', '=', 'siswa.id_user')
            ->where('p.id_tentor', $idTentor)
            ->whereNotNull('p.id_jadwal_lama')
            ->where('p.status', 'diubah')
            ->orderByDesc('p.created_at')
            ->select(
                'p.id_pengajuan',
                'kursus.nama_kursus as kursus',
                'siswa.nama as siswa',
                'p.tanggal_baru',
                'p.jam_mulai',
                'p.jam_selesai',
                'p.alasan',
                'p.created_at'
            )
            ->get()
            ->map(function ($r) {
                $dataLama = json_decode($r->alasan, true);

                $r->tanggal_lama = $dataLama['tanggal_lama'] ?? null;
                $r->mulai_lama = $dataLama['mulai_lama'] ?? null;
                $r->selesai_lama = $dataLama['selesai_lama'] ?? null;
                $r->catatan = $dataLama['catatan'] ?? null;

                return $r;
            });

        return view('tentor.jadwal', compact(
            'jadwal',
            'jadwalKalender',
            'jadwalMingguan',
            'riwayatPerubahan',
            'namaTentor',
            'prevWeek',
            'nextWeek',
            'labelMinggu'
        ));
    }

    // Mengubah jadwal secara langsung
    public function ubah(Request $request)
    {
        $idTentor = auth()->id();

        $data = $request->validate([
            'id_jadwal' => [
                'required',
                'integer',
                'exists:jadwal,id_jadwal'
            ],
            'tanggal' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'jam_mulai' => [
                'required',
                'date_format:H:i'
            ],
            'catatan' => [
                'nullable',
                'string',
                'max:500'
            ]
        ]);

        $jamMulai = Carbon::createFromFormat(
            'H:i',
            $data['jam_mulai']
        );

        // Durasi setiap pertemuan adalah 2 jam
        $jamSelesai = $jamMulai->copy()->addHours(2);

        if ($jamSelesai->format('H:i') > '23:59') {
            return response()->json([
                'message' => 'Jam selesai tidak boleh melewati pukul 23:59.'
            ], 422);
        }

        try {
            DB::transaction(function () use (
                $data,
                $idTentor,
                $jamMulai,
                $jamSelesai
            ) {
                // Ambil jadwal lama dan kunci selama transaksi
                $lama = DB::table('jadwal')
                    ->where('id_jadwal', $data['id_jadwal'])
                    ->where('id_tentor', $idTentor)
                    ->where('status', 'terjadwal')
                    ->lockForUpdate()
                    ->first();

                if (!$lama) {
                    abort(
                        403,
                        'Jadwal bukan milikmu atau sudah tidak dapat diubah.'
                    );
                }

                // Simpan data jadwal lama sebagai JSON
                $snapshotLama = [
                    'tanggal_lama' => $lama->tanggal,
                    'mulai_lama' => Carbon::parse($lama->jam_mulai)->format('H:i'),
                    'selesai_lama' => Carbon::parse($lama->jam_selesai)->format('H:i'),
                    'catatan' => $data['catatan'] ?? null
                ];

                // Simpan riwayat perubahan
                DB::table('pengajuan_jadwal')->insert([
                    'id_jadwal_lama' => $lama->id_jadwal,
                    'id_kelas' => $lama->id_kelas,
                    'id_tentor' => $idTentor,
                    'tanggal_baru' => $data['tanggal'],
                    'jam_mulai' => $jamMulai->format('H:i:s'),
                    'jam_selesai' => $jamSelesai->format('H:i:s'),
                    'alasan' => json_encode($snapshotLama),
                    'status' => 'diubah',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Perbarui jadwal secara langsung
                DB::table('jadwal')
                    ->where('id_jadwal', $lama->id_jadwal)
                    ->where('id_tentor', $idTentor)
                    ->update([
                        'tanggal' => $data['tanggal'],
                        'jam_mulai' => $jamMulai->format('H:i:s'),
                        'jam_selesai' => $jamSelesai->format('H:i:s')
                    ]);
            });

            return response()->json([
                'message' => 'Jadwal berhasil diubah.'
            ]);

        } catch (QueryException $e) {
            $pesan = strtolower($e->getMessage());

            if (
                str_contains($pesan, 'jadwal bentrok') ||
                str_contains($pesan, 'bentrok')
            ) {
                return response()->json([
                    'message' => 'Jadwal bentrok dengan jadwal tentor lainnya.'
                ], 422);
            }

            return response()->json([
                'message' => 'Terjadi kesalahan saat mengubah jadwal.'
            ], 500);
        }
    }
}
