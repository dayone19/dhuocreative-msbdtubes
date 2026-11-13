<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function index()
    {
        $idSiswa = auth()->id();

        $sertifikat = DB::table('sertifikat')
            ->join('users as tentor', 'sertifikat.id_tentor', '=', 'tentor.id_user')
            ->where('sertifikat.id_siswa', $idSiswa)
            ->orderByDesc('sertifikat.tanggal_upload')
            ->select(
                'sertifikat.id_sertifikat',
                'sertifikat.nama_projek',
                'sertifikat.tanggal_pengumpulan',
                'sertifikat.file_sertifikat',
                'tentor.nama as nama_tentor'
            )
            ->get()
            ->map(function ($s) {
                $tanggal = Carbon::parse($s->tanggal_pengumpulan);
                $ext     = strtolower(pathinfo($s->file_sertifikat, PATHINFO_EXTENSION));

                $s->tanggal_teks = $tanggal->locale('id')->translatedFormat('j F Y');
                $s->nomor        = 'DHC-' . $tanggal->year . '-' . str_pad($s->id_sertifikat, 4, '0', STR_PAD_LEFT);
                $s->adalah_gambar = in_array($ext, ['jpg', 'jpeg', 'png']);   
                $s->label_unduh  = $ext === 'pdf' ? 'Unduh PDF' : 'Unduh Gambar';
                $s->url          = route('siswa.sertifikat.file', ['sertifikat' => $s->id_sertifikat]);
                $s->url_unduh    = route('siswa.sertifikat.file', ['sertifikat' => $s->id_sertifikat, 'unduh' => 1]);
                return $s;
            });

        $jumlahSertifikat = $sertifikat->count();
        $kursusSelesai = DB::table('kelas')->where('id_siswa', $idSiswa)->where('status', 'selesai')->count();
        $dalamProses   = DB::table('kelas')->where('id_siswa', $idSiswa)->where('status', 'aktif')->count();

        return view('siswa.sertifikat', compact('sertifikat', 'jumlahSertifikat', 'kursusSelesai', 'dalamProses'));
    }

    public function file(\Illuminate\Http\Request $request, $idSertifikat)
    {
        $sertifikat = DB::table('sertifikat')
            ->where('id_sertifikat', $idSertifikat)
            ->where('id_siswa', auth()->id())
            ->first();

        abort_if(!$sertifikat, 404);

        $path = str_contains($sertifikat->file_sertifikat, '/')
            ? $sertifikat->file_sertifikat
            : "sertifikat/{$sertifikat->file_sertifikat}";

        abort_if(str_contains($path, '..'), 404);                         
        abort_unless(Storage::disk('public')->exists($path), 404);

        $nama = preg_replace('/^\d+_/', '', basename($path));              

        return $request->boolean('unduh')
            ? Storage::disk('public')->download($path, $nama)
            : Storage::disk('public')->response($path, $nama);
    }
}