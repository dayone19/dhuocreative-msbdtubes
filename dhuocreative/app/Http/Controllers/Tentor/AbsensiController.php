<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{

    public function show($kursus)
    {
        $idTentor = auth()->id();

        $kelas = DB::table('kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as siswa', 'kelas.id_siswa', '=', 'siswa.id_user')
            ->where('kelas.id_kelas', $kursus)
            ->whereExists(function ($q) use ($idTentor) {
                $q->select(DB::raw(1))->from('jadwal')
                  ->whereColumn('jadwal.id_kelas', 'kelas.id_kelas')
                  ->where('jadwal.id_tentor', $idTentor);
            })
            ->select(
                'kelas.id_kelas',
                'kelas.id_siswa as id',
                'siswa.nama',
                'kursus.nama_kursus as kursus',
                DB::raw('initcap(kelas.kelas::text) as mode')
            )
            ->first();

        abort_if(!$kelas, 404);

        $rows = DB::table('jadwal')
            ->leftJoin('absensi_siswa as a', function ($j) use ($kelas) {
                $j->on('a.id_jadwal', '=', 'jadwal.id_jadwal')
                  ->where('a.id_siswa', '=', $kelas->id);
            })
            ->where('jadwal.id_kelas', $kelas->id_kelas)
            ->where('jadwal.id_tentor', $idTentor)
            ->where('jadwal.status', '!=', 'dibatalkan')
            ->orderBy('jadwal.tanggal')
            ->orderBy('jadwal.jam_mulai')
            ->select(
                'jadwal.id_jadwal',
                DB::raw("to_char(jadwal.tanggal, 'YYYY-MM-DD') as tanggal"),
                DB::raw("to_char(jadwal.jam_mulai, 'HH24.MI') as mulai"),
                DB::raw("to_char(jadwal.jam_selesai, 'HH24.MI') as selesai"),
                DB::raw('jadwal.status::text as status_jadwal'),
                DB::raw('a.status::text as status_absen')
            )
            ->get();

        $hariIni = today()->toDateString();

        $daftarPertemuan = $rows->values()->map(function ($r, $i) use ($hariIni) {
            $tgl = Carbon::parse($r->tanggal)->locale('id');
            return [
                'ke'           => $i + 1,
                'id_jadwal'    => $r->id_jadwal,
                'tanggal'      => $r->tanggal,
                'tanggal_fmt'  => $tgl->translatedFormat('d M Y'),
                'hari'         => $tgl->translatedFormat('l'),
                'jam'          => $r->mulai . '–' . $r->selesai,
                'status_jadwal'=> ucfirst($r->status_jadwal),
                'status'       => $r->status_absen === null ? 'belum' : ($r->status_absen === 'alpha' ? 'alpa' : $r->status_absen),
                'bisa_diubah'  => $r->tanggal <= $hariIni,
            ];
        });

        $mulai = DB::table('siswa_kelas')
            ->where('id_siswa', $kelas->id)
            ->where('id_kelas', $kelas->id_kelas)
            ->value('tanggal_daftar');

        $siswa = [
            'id'            => $kelas->id,
            'nama'          => $kelas->nama,
            'kursus'        => $kelas->kursus,
            'mode'          => $kelas->mode,
            'mulai_kontrak' => $mulai ?: $rows->first()->tanggal ?? null,
            'akhir_kontrak' => $rows->last()->tanggal ?? null, 
        ];

        $total = $daftarPertemuan->count();
        $hadir = $daftarPertemuan->where('status', 'hadir')->count();
        $sisa  = $daftarPertemuan->where('status', 'belum')->count();

        return view('tentor.absensi', compact('siswa', 'daftarPertemuan', 'total', 'hadir', 'sisa'));
    }

    public function updateStatus(Request $request, $jadwal)
    {
        $data = $request->validate([
            'status' => ['required', 'in:hadir,sakit,izin,alpa'],
        ]);
        $statusDb = $data['status'] === 'alpa' ? 'alpha' : $data['status'];

        $row = DB::table('jadwal')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->where('jadwal.id_jadwal', $jadwal)
            ->where('jadwal.id_tentor', auth()->id())   
            ->select('jadwal.id_jadwal', 'jadwal.tanggal', DB::raw('jadwal.status::text as status'), 'kelas.id_siswa')
            ->first();

        abort_if(!$row, 404);

        if ($row->status === 'dibatalkan') {
            return response()->json(['message' => 'Jadwal ini dibatalkan, absensi tidak bisa diisi.'], 422);
        }
        if ($row->tanggal > today()->toDateString()) {
            return response()->json(['message' => 'Pertemuan ini belum berlangsung.'], 422);
        }

        DB::transaction(function () use ($row, $statusDb) {
            DB::statement('LOCK TABLE absensi_siswa IN SHARE ROW EXCLUSIVE MODE');

            $sudahAda = DB::table('absensi_siswa')
                ->where('id_jadwal', $row->id_jadwal)
                ->where('id_siswa', $row->id_siswa)
                ->exists();

            if ($sudahAda) {
                DB::table('absensi_siswa')
                    ->where('id_jadwal', $row->id_jadwal)
                    ->where('id_siswa', $row->id_siswa)
                    ->update(['status' => $statusDb, 'waktu_absen' => now()]);
            } else {
                DB::table('absensi_siswa')->insert([
                    'id_absensi'  => ((int) DB::table('absensi_siswa')->max('id_absensi')) + 1,
                    'id_jadwal'   => $row->id_jadwal,
                    'id_siswa'    => $row->id_siswa,
                    'waktu_absen' => now(),
                    'status'      => $statusDb,
                ]);
            }
        });

        return response()->json([
            'message' => 'Status kehadiran disimpan.',
            'status'  => $data['status'],
        ]);
    }
}