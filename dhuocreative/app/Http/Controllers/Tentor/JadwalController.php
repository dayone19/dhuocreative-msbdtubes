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

        // SQL: Mengambil seluruh jadwal yang ditugaskan kepada tentor login
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
            'minggu' => ['nullable', 'date_format:Y-m-d'],
        ])['minggu'] ?? null;

        $awalMinggu = $minggu
            ? Carbon::parse($minggu, 'Asia/Jakarta')->startOfWeek(Carbon::MONDAY)
            : Carbon::now('Asia/Jakarta')->startOfWeek(Carbon::MONDAY);

        $akhirMinggu = $awalMinggu->copy()->addDays(5);

        $prevWeek = $awalMinggu->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $awalMinggu->copy()->addWeek()->format('Y-m-d');

        $labelMinggu = $awalMinggu->translatedFormat('d M Y')
            . ' – ' . $akhirMinggu->translatedFormat('d M Y');

        // SQL: Mengambil jadwal mingguan milik tentor login
        $jadwalKalender = DB::table('vw_jadwal_tentor')
            ->where('id_tentor', $idTentor)
            ->whereBetween('tanggal', [
                $awalMinggu->toDateString(),
                $akhirMinggu->toDateString(),
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

        $hariList = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $jadwalMingguan = [];

        foreach ($hariList as $isoDay => $namaHari) {
            $tanggalHari = $awalMinggu->copy()
                ->addDays($isoDay - 1)
                ->format('Y-m-d');

            $jadwalHari = $jadwalKalender
                ->filter(function ($j) use ($tanggalHari) {
                    return $j->tanggal === $tanggalHari
                        && $j->status === 'terjadwal';
                })
                ->values();

            $jadwalMingguan[$namaHari] = [
                'tanggal' => $awalMinggu->copy()
                    ->addDays($isoDay - 1)
                    ->format('d M'),
                'jadwals' => $jadwalHari,
            ];
        }

        // Riwayat pengajuan jadwal milik tentor login
        $pengajuan = DB::table('pengajuan_jadwal as p')
            ->join('kelas', 'p.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as siswa', 'kelas.id_siswa', '=', 'siswa.id_user')
            ->leftJoin('jadwal as lama', 'p.id_jadwal_lama', '=', 'lama.id_jadwal')
            ->where('p.id_tentor', $idTentor)
            ->orderByDesc('p.created_at')
            ->select(
                'p.id_pengajuan',
                'kursus.nama_kursus as kursus',
                'kelas.kelas as kelas',
                'siswa.nama as siswa',
                DB::raw("to_char(lama.tanggal, 'YYYY-MM-DD') as \"lamaTanggal\""),
                DB::raw("to_char(lama.jam_mulai, 'HH24:MI') as \"lamaMulai\""),
                DB::raw("to_char(lama.jam_selesai, 'HH24:MI') as \"lamaSelesai\""),
                DB::raw("to_char(p.tanggal_baru, 'YYYY-MM-DD') as tanggal"),
                DB::raw("to_char(p.jam_mulai, 'HH24:MI') as mulai"),
                DB::raw("to_char(p.jam_selesai, 'HH24:MI') as selesai"),
                DB::raw('p.status::text as status'),
                DB::raw("coalesce(nullif(p.alasan, ''), case p.status::text when 'menunggu' then 'Menunggu verifikasi operator' else '' end) as ket")
            )
            ->get();

        // Pilihan kelas untuk dropdown pengajuan
        $kelasOptions = DB::table('kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as siswa', 'kelas.id_siswa', '=', 'siswa.id_user')
            ->where('kelas.status', 'aktif')
            ->where(function ($q) use ($idTentor) {
                $q->whereIn('kelas.id_kursus', function ($s) use ($idTentor) {
                    $s->select('id_kursus')
                        ->from('tentor_kursus')
                        ->where('id_tentor', $idTentor);
                })->orWhereIn('kelas.id_kelas', function ($s) use ($idTentor) {
                    $s->select('id_kelas')
                        ->from('jadwal')
                        ->where('id_tentor', $idTentor);
                });
            })
            ->orderBy('kursus.nama_kursus')
            ->select(
                'kelas.id_kelas',
                'kursus.nama_kursus as kursus',
                DB::raw('kelas.kelas::text as kelas'),
                'siswa.nama as siswa'
            )
            ->get();

        return view('tentor.jadwal', compact(
            'jadwal',
            'jadwalKalender',
            'jadwalMingguan',
            'pengajuan',
            'kelasOptions',
            'namaTentor',
            'prevWeek',
            'nextWeek',
            'labelMinggu'
        ));
    }

    /**
     * POST: Ajukan jadwal baru / perubahan jadwal
     */
    public function ajukan(Request $request)
    {
        $idTentor = auth()->id();

        $data = $request->validate([
            'id_kelas'       => ['required', 'integer', 'exists:kelas,id_kelas'],
            'id_jadwal_lama' => ['nullable', 'integer', 'exists:jadwal,id_jadwal'],
            'tanggal'        => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'      => ['required', 'date_format:H:i'],
            'catatan'        => ['nullable', 'string', 'max:500'],
        ]);

        // Memastikan kelas merupakan tanggung jawab tentor
        $kelasValid = DB::table('kelas')
            ->where('id_kelas', $data['id_kelas'])
            ->where(function ($q) use ($idTentor) {
                $q->whereIn('id_kursus', function ($s) use ($idTentor) {
                    $s->select('id_kursus')
                        ->from('tentor_kursus')
                        ->where('id_tentor', $idTentor);
                })->orWhereIn('id_kelas', function ($s) use ($idTentor) {
                    $s->select('id_kelas')
                        ->from('jadwal')
                        ->where('id_tentor', $idTentor);
                });
            })
            ->exists();

        if (!$kelasValid) {
            return response()->json([
                'message' => 'Kamu tidak terdaftar sebagai tentor pada kelas ini.'
            ], 422);
        }

        // Menghitung jam selesai otomatis, yaitu 2 jam setelah jam mulai
        $jamMulai = Carbon::createFromFormat('H:i', $data['jam_mulai']);

        if ($jamMulai->format('H:i') > '21:59') {
            return response()->json([
                'message' => 'Jam mulai maksimal pukul 21:59 agar durasi 2 jam tidak melewati hari berikutnya.'
            ], 422);
        }

        $jamSelesai = $jamMulai->copy()->addHours(2);

        // Memastikan jadwal lama milik tentor yang sedang login
        if (!empty($data['id_jadwal_lama'])) {
            $lama = DB::table('jadwal')
                ->where('id_jadwal', $data['id_jadwal_lama'])
                ->where('id_tentor', $idTentor)
                ->where('status', 'terjadwal')
                ->first();

            abort_unless(
                $lama,
                403,
                'Jadwal ini bukan milikmu atau sudah tidak bisa diubah.'
            );

            if ((int) $lama->id_kelas !== (int) $data['id_kelas']) {
                return response()->json([
                    'message' => 'Kelas tidak sesuai dengan jadwal yang diubah.'
                ], 422);
            }

            // Mencegah pengajuan ganda untuk jadwal yang sama
            $dobel = DB::table('pengajuan_jadwal')
                ->where('id_jadwal_lama', $data['id_jadwal_lama'])
                ->where('status', 'menunggu')
                ->exists();

            if ($dobel) {
                return response()->json([
                    'message' => 'Perubahan untuk jadwal ini masih menunggu verifikasi.'
                ], 422);
            }
        }

        // Menyimpan pengajuan jadwal
        try {
            DB::table('pengajuan_jadwal')->insert([
                'id_tentor'      => $idTentor,
                'id_kelas'       => $data['id_kelas'],
                'id_jadwal_lama' => $data['id_jadwal_lama'] ?? null,
                'tanggal_baru'   => $data['tanggal'],
                'jam_mulai'      => $data['jam_mulai'],
                'jam_selesai'    => $jamSelesai->format('H:i:s'),
                'alasan'         => $data['catatan'] ?? null,
                'status'         => 'menunggu',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'Jadwal bentrok')) {
                return response()->json([
                    'message' => 'Jadwal bentrok dengan jadwal tentor lain atau pengajuan yang masih menunggu.'
                ], 422);
            }

            return response()->json([
                'message' => 'Gagal mengirim pengajuan jadwal.'
            ], 500);
        }

        return response()->json([
            'message' => empty($data['id_jadwal_lama'])
                ? 'Pengajuan jadwal terkirim.'
                : 'Permintaan perubahan jadwal terkirim.',
        ]);
    }
}