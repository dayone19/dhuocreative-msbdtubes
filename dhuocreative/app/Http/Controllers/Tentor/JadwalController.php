<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    private const PER_PAGE = 5;

    /** Halaman Jadwal Saya — pagination + filter di controller (server-side) */
    public function index(Request $request)
    {
        $idTentor = auth()->id();

        $q       = trim((string) $request->get('q', ''));
        $tanggal = $request->get('tanggal');
        $status  = $request->get('status', 'terjadwal'); // default sama seperti sebelumnya

        // Query dasar untuk tabel (paginated)
        $base = DB::table('jadwal')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as siswa', 'kelas.id_siswa', '=', 'siswa.id_user')
            ->where('jadwal.id_tentor', $idTentor)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('kursus.nama_kursus', 'ilike', "%{$q}%")
                      ->orWhere('siswa.nama', 'ilike', "%{$q}%")
                      ->orWhereRaw('kelas.kelas::text ilike ?', ["%{$q}%"]);
                });
            })
            ->when($tanggal, fn ($query) => $query->whereDate('jadwal.tanggal', $tanggal))
            ->when($status !== '' && $status !== null, fn ($query) => $query->where('jadwal.status', $status));

        $jadwal = (clone $base)
            ->orderByDesc('jadwal.tanggal')
            ->orderByDesc('jadwal.jam_mulai')
            ->select(
                'jadwal.id_jadwal',
                'kelas.id_kelas',
                'kursus.nama_kursus as kursus',
                DB::raw('kelas.kelas::text as kelas'),
                'siswa.nama as siswa',
                DB::raw("to_char(jadwal.tanggal, 'YYYY-MM-DD') as tanggal"),
                DB::raw("to_char(jadwal.jam_mulai, 'HH24:MI') as mulai"),
                DB::raw("to_char(jadwal.jam_selesai, 'HH24:MI') as selesai"),
                DB::raw('jadwal.status::text as status')
            )
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        // Data penuh (atau rentang minggu ini) khusus kalender mingguan — tanpa pagination
        $jadwalKalender = DB::table('jadwal')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->where('jadwal.id_tentor', $idTentor)
            ->where('jadwal.status', 'terjadwal')
            ->whereBetween('jadwal.tanggal', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString(),
            ])
            ->orderBy('jadwal.tanggal')
            ->orderBy('jadwal.jam_mulai')
            ->select(
                'jadwal.id_jadwal',
                'kursus.nama_kursus as kursus',
                DB::raw('kelas.kelas::text as kelas'),
                DB::raw("to_char(jadwal.tanggal, 'YYYY-MM-DD') as tanggal"),
                DB::raw("to_char(jadwal.jam_mulai, 'HH24:MI') as mulai"),
                DB::raw("to_char(jadwal.jam_selesai, 'HH24:MI') as selesai"),
                DB::raw('jadwal.status::text as status')
            )
            ->get();

        // riwayat pengajuan
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

        // pilihan kelas untuk dropdown
        $kelasOptions = DB::table('kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as siswa', 'kelas.id_siswa', '=', 'siswa.id_user')
            ->where('kelas.status', 'aktif')
            ->where(function ($q) use ($idTentor) {
                $q->whereIn('kelas.id_kursus', function ($s) use ($idTentor) {
                    $s->select('id_kursus')->from('tentor_kursus')->where('id_tentor', $idTentor);
                })->orWhereIn('kelas.id_kelas', function ($s) use ($idTentor) {
                    $s->select('id_kelas')->from('jadwal')->where('id_tentor', $idTentor);
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
            'pengajuan',
            'kelasOptions',
            'q',
            'tanggal',
            'status'
        ));
    }

    /** POST: ajukan jadwal baru / perubahan jadwal */
    public function ajukan(Request $request)
    {
        $idTentor = auth()->id();

        $data = $request->validate([
            'id_kelas'       => ['required', 'integer', 'exists:kelas,id_kelas'],
            'id_jadwal_lama' => ['nullable', 'integer', 'exists:jadwal,id_jadwal'],
            'tanggal'        => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'      => ['required', 'date_format:H:i'],
            'jam_selesai'    => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'catatan'        => ['nullable', 'string', 'max:500'],
        ]);

        if (!empty($data['id_jadwal_lama'])) {
            $lama = DB::table('jadwal')
                ->where('id_jadwal', $data['id_jadwal_lama'])
                ->where('id_tentor', $idTentor)
                ->where('status', 'terjadwal')
                ->first();
            abort_unless($lama, 403, 'Jadwal ini bukan milikmu atau sudah tidak bisa diubah.');

            if ((int) $lama->id_kelas !== (int) $data['id_kelas']) {
                return response()->json(['message' => 'Kelas tidak sesuai dengan jadwal yang diubah.'], 422);
            }
        }

        $bentrok = DB::table('jadwal')
            ->where('id_tentor', $idTentor)
            ->where('status', '!=', 'dibatalkan')
            ->whereDate('tanggal', $data['tanggal'])
            ->when(!empty($data['id_jadwal_lama']), fn ($q) => $q->where('id_jadwal', '!=', $data['id_jadwal_lama']))
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->exists();

        if ($bentrok) {
            return response()->json(['message' => 'Jadwal bentrok dengan jadwal lain milikmu.'], 422);
        }

        if (!empty($data['id_jadwal_lama'])) {
            $dobel = DB::table('pengajuan_jadwal')
                ->where('id_jadwal_lama', $data['id_jadwal_lama'])
                ->where('status', 'menunggu')
                ->exists();
            if ($dobel) {
                return response()->json(['message' => 'Perubahan untuk jadwal ini masih menunggu verifikasi.'], 422);
            }
        }

        DB::table('pengajuan_jadwal')->insert([
            'id_tentor'      => $idTentor,
            'id_kelas'       => $data['id_kelas'],
            'id_jadwal_lama' => $data['id_jadwal_lama'] ?? null,
            'tanggal_baru'   => $data['tanggal'],
            'jam_mulai'      => $data['jam_mulai'],
            'jam_selesai'    => $data['jam_selesai'],
            'alasan'         => $data['catatan'] ?? null,
            'status'         => 'menunggu',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json([
            'message' => empty($data['id_jadwal_lama'])
                ? 'Pengajuan jadwal terkirim.'
                : 'Permintaan perubahan jadwal terkirim.',
        ]);
    }
}