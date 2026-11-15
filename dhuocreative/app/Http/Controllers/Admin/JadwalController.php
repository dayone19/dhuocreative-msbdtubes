<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $idTentor = Auth::id();

        $q = $request->q;
        $tanggal = $request->tanggal;
        $status = $request->status;

        // SELECT jadwal tentor dan join
        $queryJadwal = DB::table('jadwal as j')
            ->join('kelas as k', 'j.id_kelas', '=', 'k.id_kelas')
            ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
            ->join('users as us', 'k.id_siswa', '=', 'us.id_user')
            ->where('j.id_tentor', $idTentor)
            ->select(
                'j.id_jadwal',
                'j.id_kelas',
                'ku.nama_kursus as kursus',
                'k.jenis_kelas as kelas',
                'us.nama as siswa',
                DB::raw("TO_CHAR(j.tanggal, 'YYYY-MM-DD') as tanggal"),
                DB::raw("TO_CHAR(j.jam_mulai, 'HH24:MI') as mulai"),
                DB::raw("TO_CHAR(j.jam_selesai, 'HH24:MI') as selesai"),
                'j.status'
            );

        // Pencarian kursus, kelas, dan siswa
        if ($request->filled('q')) {
            $queryJadwal->where(function ($query) use ($q) {
                $query->where('ku.nama_kursus', 'ILIKE', "%{$q}%")
                    ->orWhere('k.jenis_kelas', 'ILIKE', "%{$q}%")
                    ->orWhere('us.nama', 'ILIKE', "%{$q}%");
            });
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $queryJadwal->whereDate('j.tanggal', $tanggal);
        }

        // Filter status
        if ($request->filled('status')) {
            $queryJadwal->where('j.status', $status);
        }

        $jadwal = $queryJadwal
            ->orderBy('j.tanggal')
            ->orderBy('j.jam_mulai')
            ->paginate(5)
            ->withQueryString();

        // SELECT jadwal tentor untuk kalender mingguan
        $jadwalKalender = DB::table('jadwal as j')
            ->join('kelas as k', 'j.id_kelas', '=', 'k.id_kelas')
            ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
            ->where('j.id_tentor', $idTentor)
            ->whereBetween('j.tanggal', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString()
            ])
            ->select(
                'j.id_jadwal',
                'ku.nama_kursus as kursus',
                'k.jenis_kelas as kelas',
                DB::raw("TO_CHAR(j.tanggal, 'YYYY-MM-DD') as tanggal"),
                DB::raw("TO_CHAR(j.jam_mulai, 'HH24:MI') as mulai"),
                DB::raw("TO_CHAR(j.jam_selesai, 'HH24:MI') as selesai"),
                'j.status'
            )
            ->orderBy('j.tanggal')
            ->orderBy('j.jam_mulai')
            ->get();

        // SELECT pengajuan perubahan jadwal milik tentor
        $pengajuan = DB::table('pengajuan_jadwal as p')
            ->join('jadwal as jl', 'p.id_jadwal_lama', '=', 'jl.id_jadwal')
            ->join('kelas as k', 'p.id_kelas', '=', 'k.id_kelas')
            ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
            ->join('users as us', 'k.id_siswa', '=', 'us.id_user')
            ->where('p.id_tentor', $idTentor)
            ->select(
                'p.id_pengajuan',
                'ku.nama_kursus as kursus',
                'us.nama as siswa',
                DB::raw("TO_CHAR(jl.tanggal, 'YYYY-MM-DD') as lamaTanggal"),
                DB::raw("TO_CHAR(jl.jam_mulai, 'HH24:MI') as lamaMulai"),
                DB::raw("TO_CHAR(jl.jam_selesai, 'HH24:MI') as lamaSelesai"),
                DB::raw("TO_CHAR(p.tanggal_baru, 'YYYY-MM-DD') as tanggal"),
                DB::raw("TO_CHAR(p.jam_mulai, 'HH24:MI') as mulai"),
                DB::raw("TO_CHAR(p.jam_selesai, 'HH24:MI') as selesai"),
                'p.status',
                'p.alasan as ket'
            )
            ->orderByDesc('p.created_at')
            ->get();

        return view('tentor.jadwal', compact(
            'jadwal',
            'jadwalKalender',
            'pengajuan',
            'q',
            'tanggal',
            'status'
        ));
    }
}
