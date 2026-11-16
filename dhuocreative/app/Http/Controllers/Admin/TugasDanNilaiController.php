<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TugasDanNilaiController extends Controller
{
    public function index()
    {
        // nampilkan semua tugas 
        $daftarTugas = DB::table('tugas as t')
            ->join('kelas as k', 't.id_kelas', '=', 'k.id_kelas')
            ->join('kursus as ku', 'k.id_kursus', '=', 'ku.id_kursus')
            ->join('users as tentor', 't.id_tentor', '=', 'tentor.id_user')
            ->leftJoin('users as siswa', 'k.id_siswa', '=', 'siswa.id_user')
            ->leftJoin('pengumpulan_tugas as p', function ($join) {
                $join->on('p.id_tugas', '=', 't.id_tugas')
                     ->on('p.id_siswa', '=', 'k.id_siswa');
            })
            ->leftJoin('nilai as n', 'n.id_pengumpulan', '=', 'p.id_pengumpulan')
            ->select(
                't.id_tugas',
                't.judul',
                't.deskripsi',
                't.deadline',
                't.tipe',
                't.file_tugas',
                'ku.nama_kursus',
                'siswa.id_user as id_siswa',
                'siswa.nama as nama_siswa',
                'tentor.nama as nama_tentor',
                'p.id_pengumpulan',
                'p.file_jawaban',
                'p.waktu_pengumpulan',
                'p.status as status_pengumpulan',
                'n.nilai',
                'n.feedback'
            )
            ->orderByDesc('t.created_at')
            ->get();

        $daftarTugas = $daftarTugas->map(function ($item) {
            if (!$item->id_pengumpulan) {
                $item->status_label = 'Belum Mengumpulkan';
            } else {
                $status = $item->status_pengumpulan;

                $item->status_label = match ($status) {
                    'terkumpul' => 'Terkumpul',
                    'terlambat' => 'Terlambat',
                    'belum_mengumpulkan' => 'Belum Mengumpulkan',
                    default => ucfirst(str_replace('_', ' ', $status)),
                };
            }

            $item->jenis_label = ucfirst($item->tipe);

            $item->deadline_label = $item->deadline
                ? date('d M Y · H:i', strtotime($item->deadline))
                : '-';

            $item->tanggal_pengumpulan_label = $item->waktu_pengumpulan
                ? date('d M Y · H:i', strtotime($item->waktu_pengumpulan))
                : '-';

            $item->file_url = $item->file_jawaban
                ? Storage::url($item->file_jawaban)
                : null;

            return $item;
        });

        $daftarSiswa = $daftarTugas
            ->whereNotNull('nama_siswa')
            ->pluck('nama_siswa')
            ->unique()
            ->sort()
            ->values();

        $daftarTentor = $daftarTugas
            ->whereNotNull('nama_tentor')
            ->pluck('nama_tentor')
            ->unique()
            ->sort()
            ->values();

        $daftarKursus = $daftarTugas
            ->whereNotNull('nama_kursus')
            ->pluck('nama_kursus')
            ->unique()
            ->sort()
            ->values();

        return view('admin.tugas', compact(
            'daftarTugas',
            'daftarSiswa',
            'daftarTentor',
            'daftarKursus'
        ));
    }
}
