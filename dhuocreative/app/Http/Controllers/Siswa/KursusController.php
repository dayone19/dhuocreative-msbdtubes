<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class KursusController extends Controller
{
    public function index()
    {
        $idSiswa = auth()->id();   // = id_user siswa yang login

        $kursus = DB::table('kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->where('kelas.id_siswa', $idSiswa)                     
            ->orderByRaw("case when kelas.status::text = 'aktif' then 0 else 1 end")
            ->orderBy('kursus.nama_kursus')
            ->select(
                'kelas.id_kelas',
                'kursus.nama_kursus',
                'kursus.jumlah_pertemuan',                        
                DB::raw('kelas.kelas::text as jenis'),           
                DB::raw("(select u.nama from jadwal j join users u on u.id_user = j.id_tentor
                          where j.id_kelas = kelas.id_kelas order by j.tanggal limit 1) as nama_tentor"),
                DB::raw("(select count(*) from jadwal j
                          where j.id_kelas = kelas.id_kelas and j.status::text = 'selesai') as selesai")
            )
            ->get()
            ->map(function ($k) {
                $target = (int) ($k->jumlah_pertemuan ?: 15);

                $k->persen = min(100, (int) round($k->selesai / $target * 100));
                return $k;
            });

        return view('siswa.kursus', compact('kursus'));
    }
}