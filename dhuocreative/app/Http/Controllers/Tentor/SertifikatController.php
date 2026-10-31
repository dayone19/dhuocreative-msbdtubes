<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SertifikatController extends Controller
{
    public function index()
    {
        $idTentor = auth()->id();

        $data = DB::table('sertifikat')
            ->join('users', 'sertifikat.id_siswa', '=', 'users.id_user')
            ->where('sertifikat.id_tentor', $idTentor)
            ->orderByDesc('sertifikat.tanggal_upload')
            ->select(
                'sertifikat.id_sertifikat', 
                'users.nama as nama_siswa',
                'sertifikat.nama_projek',
                'sertifikat.tanggal_pengumpulan',
                'sertifikat.file_sertifikat',
                'sertifikat.tanggal_upload'
            )
            ->get();

        $sertifikat = $data->map(fn ($s) => [
            'student'    => $s->nama_siswa,
            'exam'       => $s->nama_projek,
            'examDate'   => Carbon::parse($s->tanggal_pengumpulan)->format('d/m/Y'),
            'uploadDate' => Carbon::parse($s->tanggal_upload)->format('d/m/Y H:i'),
            'year'       => (string) Carbon::parse($s->tanggal_pengumpulan)->year,
            'fileName'   => preg_replace('/^\d+_/', '', $s->file_sertifikat),   
            'fileUrl'    => route('tentor.sertifikat.file', ['sertifikat' => $s->id_sertifikat]),
            'fileType'   => $this->jenisFile($s->file_sertifikat),
        ])->values()->all();

        $siswa = $this->siswaTentor($idTentor);

        return view('tentor.sertifikat', compact('sertifikat', 'siswa'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'id_siswa'        => 'required|integer',
            'nama_ujian'       => 'required|string|max:150',
            'tanggal_ujian'   => 'required|date',
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'id_siswa.required'        => 'Pilih siswa terlebih dahulu.',
            'nama_ujian.required'      => 'Nama ujian wajib diisi.',
            'tanggal_ujian.required'   => 'Tanggal ujian wajib diisi.',
            'file_sertifikat.required' => 'File sertifikat wajib dipilih.',
            'file_sertifikat.mimes'    => 'Format file harus PDF, JPG, JPEG, atau PNG.',
            'file_sertifikat.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $idSiswa = (int) $request->id_siswa;
        abort_unless($this->siswaTentor(auth()->id())->pluck('id_user')->contains($idSiswa), 403);

         $file = $request->file('file_sertifikat');
        $namaSimpan = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '.' . strtolower($file->getClientOriginalExtension());
        $file->storeAs('sertifikat', $namaSimpan, 'public');
 
        DB::transaction(function () use ($request, $idSiswa, $namaSimpan) {
            DB::table('sertifikat')->insert([
                'id_sertifikat'       => (int) DB::table('sertifikat')->max('id_sertifikat') + 1,
                'id_siswa'            => $idSiswa,
                'id_tentor'           => auth()->id(),
                'nama_projek'         => $request->nama_ujian,        
                'tanggal_pengumpulan' => $request->tanggal_ujian,     
                'file_sertifikat'     => $namaSimpan,
                'tanggal_upload'      => now(),
            ]);
        });
 
        return redirect()->route('tentor.sertifikat')->with('sukses', 'Sertifikat berhasil diupload.');
    }

    public function file($idSertifikat)
    {
        $sertifikat = DB::table('sertifikat')
            ->where('id_sertifikat', $idSertifikat)
            ->where('id_tentor', auth()->id())
            ->first();

         abort_if(!$sertifikat, 404);
 
        $path = "sertifikat/{$sertifikat->file_sertifikat}";
        abort_unless(Storage::disk('public')->exists($path), 404);
 
        return Storage::disk('public')->response(
            $path,
            preg_replace('/^\d+_/', '', $sertifikat->file_sertifikat)
        );
    }

    private function siswaTentor($idTentor)
    {
        return DB::table("siswa_kelas")
            ->join('users', 'siswa_kelas.id_siswa', '=', 'users.id_user')
            ->join('jadwal', 'jadwal.id_kelas', '=', 'siswa_kelas.id_kelas')
            ->where('jadwal.id_tentor', $idTentor)
            ->where('siswa_kelas.status', 'aktif')
            ->distinct()
            ->orderBy('users.nama')
            ->select('users.id_user', 'users.nama')
            ->get();
    }

    private function jenisFile(string $nama): string
    {
        return match (strtolower(pathinfo($nama, PATHINFO_EXTENSION))) {
            'pdf'         => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            default       => 'application/octet-stream',
        };
    }
}
