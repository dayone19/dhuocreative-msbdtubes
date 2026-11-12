<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = DB::table('pengumuman')
            ->join('users', 'pengumuman.id_user', '=', 'users.id_user')
            ->orderByDesc('pengumuman.tanggal')
            ->orderByDesc('pengumuman.id_pengumuman')
            ->select(
                'pengumuman.id_pengumuman',
                'pengumuman.judul',
                'pengumuman.isi',
                'pengumuman.tanggal',
                'users.nama',
                'users.role'
            )
            ->get()
            ->map(function ($p) {
                
                $p->tanggal_iso = Carbon::parse($p->tanggal)->toDateString();
                $p->tanggal_teks = Carbon::parse($p->tanggal)->locale('id');
                $p->pembuat_teks = $p->nama . ' (' . ucfirst($p->role) . ')';
                return $p;
            });
            
            return view('siswa.pengumuman', compact('pengumuman'));
    }
}
