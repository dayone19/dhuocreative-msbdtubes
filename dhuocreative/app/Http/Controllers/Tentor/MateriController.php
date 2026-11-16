<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;   
use Illuminate\Support\Str;               

class MateriController extends Controller
{
    public function index()
    {
        $kelasTentor = $this->kelasTentor(auth()->id());
        $disk = Storage::disk('public');

        $materi = [];

        foreach ($kelasTentor as $kelas) {
            foreach ($disk->files("materi/{$kelas->id_kelas}") as $path) {
                if (!str_ends_with($path, '.json')) {
                    continue;
                }

                $meta = json_decode($disk->get($path), true);
                if (!$meta) {
                    continue;
                }

                $materi[] = [
                    'id_kelas'       => $kelas->id_kelas,
                    'nama_kelas'     => $kelas->nama_kelas,
                    'token'          => basename($path, '.json'),
                    'judul'          => $meta['judul'],
                    'deskripsi'      => $meta['deskripsi'] ?? '',
                    'nama_file'      => $meta['nama_file_asli'],
                    'dibuat'         => $meta['dibuat'],
                    'milik_sendiri'  => (int) ($meta['id_tentor'] ?? 0) === (int) auth()->id(),
                ];
            }
        }

        usort($materi, fn ($a, $b) => strcmp($b['dibuat'], $a['dibuat']));

        return view('tentor.materi', compact('materi', 'kelasTentor'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:150',
            'id_kelas'    => 'required|integer',
            'deskripsi'   => 'nullable|string',
            'file_materi' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:51200',   
        ], [
            'judul.required'       => 'Judul materi wajib diisi.',
            'id_kelas.required'    => 'Pilih kelas terlebih dahulu.',
            'file_materi.required' => 'File materi wajib dipilih.',
            'file_materi.mimes'    => 'File harus berjenis pdf, doc, docx, ppt, pptx, atau zip.',
            'file_materi.max'      => 'Ukuran file maksimal 50 MB.',
        ]);

        $idKelas = (int) $request->id_kelas;
        abort_unless(in_array($idKelas, $this->kelasTentor(auth()->id())->pluck('id_kelas')->all()), 403);

        $file   = $request->file('file_materi');
        $disk   = Storage::disk('public');
        $token  = (string) Str::uuid();                                 
        $ext    = strtolower($file->getClientOriginalExtension());
        $folder = "materi/{$idKelas}";

        $disk->putFileAs($folder, $file, "{$token}.{$ext}");

        $disk->put("{$folder}/{$token}.json", json_encode([
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'nama_file_asli' => $file->getClientOriginalName(),
            'file'           => "{$folder}/{$token}.{$ext}",
            'id_tentor'      => auth()->id(),
            'dibuat'         => now()->toDateTimeString(),
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return redirect()->route('tentor.materi')->with('sukses', 'Materi berhasil diupload.');
    }

    public function update(Request $request, $idKelas, $token)
    {
        [$jsonPath, $meta] = $this->temukanMateri($idKelas, $token, true);

        $request->validate([
            'judul'       => 'required|string|max:150',
            'deskripsi'   => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:51200',
        ], [
            'judul.required'    => 'Judul materi wajib diisi.',
            'file_materi.mimes' => 'File harus berjenis pdf, doc, docx, ppt, pptx, atau zip.',
            'file_materi.max'   => 'Ukuran file maksimal 50 MB.',
        ]);

        $disk = Storage::disk('public');

        $meta['judul']     = $request->judul;
        $meta['deskripsi'] = $request->deskripsi;
        $meta['diubah']    = now()->toDateTimeString();

        if ($request->hasFile('file_materi')) {
            $file     = $request->file('file_materi');
            $ext      = strtolower($file->getClientOriginalExtension());
            $folder   = 'materi/' . (int) $idKelas;
            $pathBaru = "{$folder}/{$token}.{$ext}";

            $disk->putFileAs($folder, $file, "{$token}.{$ext}");

            if ($meta['file'] !== $pathBaru) {
                $disk->delete($meta['file']);
            }

            $meta['file']           = $pathBaru;
            $meta['nama_file_asli'] = $file->getClientOriginalName();
        }

        $disk->put($jsonPath, json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return redirect()->route('tentor.materi')->with('sukses', 'Materi berhasil diperbarui.');
    }

    public function hapus($idKelas, $token)
    {
        [$jsonPath, $meta] = $this->temukanMateri($idKelas, $token, true);

        $disk = Storage::disk('public');
        $disk->delete($meta['file']);  
        $disk->delete($jsonPath);       

        return redirect()->route('tentor.materi')->with('sukses', 'Materi berhasil dihapus.');
    }

    public function file(Request $request, $idKelas, $token)
    {
        [, $meta] = $this->temukanMateri($idKelas, $token, false);

        $disk = Storage::disk('public');
        abort_unless($disk->exists($meta['file']), 404);

        if ($request->boolean('unduh')) {
            return $disk->download($meta['file'], $meta['nama_file_asli']);
        }

        return $disk->response($meta['file'], $meta['nama_file_asli']);
    }

    private function temukanMateri($idKelas, $token, bool $harusMilikSendiri): array
    {
        $idKelas = (int) $idKelas;

        abort_unless(in_array($idKelas, $this->kelasTentor(auth()->id())->pluck('id_kelas')->all()), 404);
        abort_unless(preg_match('/^[A-Za-z0-9\-]+$/', $token), 404);

        $disk     = Storage::disk('public');
        $jsonPath = "materi/{$idKelas}/{$token}.json";
        abort_unless($disk->exists($jsonPath), 404);

        $meta = json_decode($disk->get($jsonPath), true);
        abort_unless($meta && isset($meta['file']), 404);

        if ($harusMilikSendiri) {
            abort_unless((int) ($meta['id_tentor'] ?? 0) === (int) auth()->id(), 403);
        }

        return [$jsonPath, $meta];
    }

    private function kelasTentor($idTentor)
    {
        return DB::table('kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->where('kelas.status', 'aktif')
            ->whereExists(function ($q) use ($idTentor) {
                $q->select(DB::raw(1))
                  ->from('jadwal')
                  ->whereColumn('jadwal.id_kelas', 'kelas.id_kelas')
                  ->where('jadwal.id_tentor', $idTentor);
            })
            ->orderBy('kelas.id_kelas')
            ->select('kelas.id_kelas', $this->namaKelas())
            ->get();
    }

    private function namaKelas()
    {
        return DB::raw("kursus.nama_kursus || ' - ' || kelas.id_kelas || ' - ' || initcap(kelas.kelas::text) as nama_kelas");
    }
}