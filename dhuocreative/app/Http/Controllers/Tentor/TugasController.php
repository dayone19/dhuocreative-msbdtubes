<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TugasController extends Controller
{
    public function index()
    {
        $idTentor = auth()->id();

        $tugas = DB::table('tugas')
            ->join('kelas', 'tugas.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->where('tugas.id_tentor', $idTentor)
            ->orderByDesc('tugas.created_at')
            ->select(
                'tugas.id_tugas',
                'tugas.id_kelas', 
                'tugas.judul', 
                'tugas.deskripsi',
                'tugas.file_tugas',
                'tugas.deadline',
                'tugas.tipe',
                'tugas.created_at',
                $this->namaKelas()
            )
            ->get();

            $kelasTentor = $this->kelasTentor($idTentor);

            return view('tentor.tugas', compact('tugas', 'kelasTentor'));
    }

    public function simpan(Request $request)
    {
        $request->validate(
            $this->aturan(true),
            $this->pesan()
        );

        $this->pastikanKelasMilikTentor((int) $request->id_kelas);

        $file = $request->file('file_tugas');
        $namaSimpan = $this->simpanFile($file);

        DB::transaction(function () use ($request, $namaSimpan) {
            DB::table('tugas')->insert([
                'id_tugas'   => (int) DB::table('tugas')->max('id_tugas') + 1,
                'id_kelas'   => (int) $request->id_kelas,
                'id_tentor'  => auth()->id(),
                'judul'      => $request->judul,
                'deskripsi'  => $request->deskripsi,
                'deadline'   =>Carbon::parse($request->deadline),
                'file_tugas' => $namaSimpan,
                'tipe'       => $request->tipe,
                'created_at' => now(),
                'update_at'  => now(),
            ]);
        });

        return redirect()->route('tentor.tugas')->with('sukses', 'Tugas berhasil dibuat');
    }

     public function update(Request $request, $idTugas)
    {
        $tugas = $this->tugasMilikTentor($idTugas);
 
        $request->validate(
            $this->aturan(false),  
            $this->pesan()
        );
 
        $this->pastikanKelasMilikTentor((int) $request->id_kelas);
 
        $data = [
            'id_kelas'  => (int) $request->id_kelas,
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline'  => Carbon::parse($request->deadline),
            'tipe'      => $request->tipe,
            'update_at' => now(),
        ];
 
        if ($request->hasFile('file_tugas')) {
            $data['file_tugas'] = $this->simpanFile($request->file('file_tugas'));
            Storage::disk('public')->delete("tugas/{$tugas->file_tugas}");
        }
 
        DB::table('tugas')->where('id_tugas', $idTugas)->update($data);
 
        return redirect()->route('tentor.tugas')->with('sukses', 'Tugas berhasil diperbarui.');
    }

    public function file($idTugas)
    {
        $tugas = $this->tugasMilikTentor($idTugas);
        $path  = "tugas/{$tugas->file_tugas}";
 
        abort_unless(Storage::disk('public')->exists($path), 404);
 
        return Storage::disk('public')->response($path, $this->namaTampil($tugas->file_tugas));
    }

    private function aturan(bool $fileWajib): array
    {
        return [
            'judul'      => 'required|string|max:150',
            'id_kelas'   => 'required|integer',
            'tipe'       => 'required|in:tugas,projek',        
            'deskripsi'  => 'required|string',
            'deadline'   => 'required|date',
            'file_tugas' => ($fileWajib ? 'required' : 'nullable') . '|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:51200',
        ];
    }
 
    private function pesan(): array
    {
        return [
            'judul.required'      => 'Judul tugas wajib diisi.',
            'id_kelas.required'   => 'Pilih kelas terlebih dahulu.',
            'tipe.required'       => 'Pilih tipe tugas.',
            'deskripsi.required'  => 'Deskripsi tugas wajib diisi.',
            'deadline.required'   => 'Deadline wajib diisi.',
            'deadline.date'       => 'Format deadline tidak valid.',
            'file_tugas.required' => 'File pendukung wajib dipilih.',
            'file_tugas.mimes'    => 'File harus berjenis pdf, doc, docx, ppt, pptx, atau zip.',
            'file_tugas.max'      => 'Ukuran file maksimal 50 MB.',
        ];
    }
 
    private function simpanFile($file): string
    {
        $nama = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
              . '.' . strtolower($file->getClientOriginalExtension());
 
        $file->storeAs('tugas', $nama, 'public');
 
        return $nama;
    }

    private function namaTampil(string $namaSimpan): string
    {
        return preg_replace('/^\d+_/', '', $namaSimpan);
    }
 
    private function tugasMilikTentor($idTugas)
    {
        $tugas = DB::table('tugas')
            ->where('id_tugas', $idTugas)
            ->where('id_tentor', auth()->id())
            ->first();
 
        abort_if(!$tugas, 404);
 
        return $tugas;
    }
 
    private function pastikanKelasMilikTentor(int $idKelas): void
    {
        $boleh = $this->kelasTentor(auth()->id())->pluck('id_kelas')->all();
 
        abort_unless(in_array($idKelas, $boleh), 403);
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

