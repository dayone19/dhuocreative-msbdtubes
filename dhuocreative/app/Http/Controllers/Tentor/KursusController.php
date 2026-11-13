<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class KursusController extends Controller
{

    public function index()
    {
        $idTentor = auth()->id();

        $kelasSaya = DB::table('kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->join('users as siswa', 'kelas.id_siswa', '=', 'siswa.id_user')
            ->whereIn('kelas.id_kelas', function ($q) use ($idTentor) {
                $q->select('id_kelas')->from('jadwal')->where('id_tentor', $idTentor);
            })
            ->where('kelas.status', 'aktif')
            ->groupBy('kelas.id_kelas', 'kursus.nama_kursus', 'kelas.kelas', 'siswa.nama')
            ->orderBy('kursus.nama_kursus')
            ->orderBy('siswa.nama')
            ->select(
                'kelas.id_kelas as id',
                'kursus.nama_kursus as kursus',
                DB::raw('initcap(kelas.kelas::text) as kelas'),
                'siswa.nama as siswa',
                DB::raw("case when kelas.kelas::text = 'online' then 'teal' else 'orange' end as warna")
            )
            ->get();

        return view('tentor.kursus', compact('kelasSaya'));
    }
}