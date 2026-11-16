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
                'sertifikat.id_siswa',
                'users.nama as nama_siswa',
                'sertifikat.nama_projek',
                'sertifikat.tanggal_pengumpulan',
                'sertifikat.file_sertifikat',
                'sertifikat.tanggal_upload'
            )
            ->get();

        $sertifikat = $data->map(fn ($s) => [
            'id'         => $s->id_sertifikat,
            'studentId'  => (string) $s->id_siswa,
            'student'    => $s->nama_siswa,
            'exam'       => $s->nama_projek,
            'dateISO'    => Carbon::parse($s->tanggal_pengumpulan)->format('Y-m-d'),
            'examDate'   => Carbon::parse($s->tanggal_pengumpulan)->format('d/m/Y'),
            'uploadDate' => Carbon::parse($s->tanggal_upload)->format('d/m/Y H:i'),
            'year'       => (string) Carbon::parse($s->tanggal_pengumpulan)->year,
            'fileName'   => preg_replace('/^\d+_/', '', $s->file_sertifikat),
            'fileUrl'    => route('tentor.sertifikat.file', ['sertifikat' => $s->id_sertifikat]),
            'fileType'   => $this->jenisFile($s->file_sertifikat),
        ])->values()->all();

        $siswa = $this->siswaLayakSertifikat($idTentor);

        return view('tentor.sertifikat', compact('sertifikat', 'siswa'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'id_siswa'        => 'required|integer',
            'nama_ujian'      => 'required|string|max:150',
            'tanggal_ujian'   => 'required|date',
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], $this->pesan());

        $idTentor = auth()->id();
        $idSiswa  = (int) $request->id_siswa;

        if (!$this->sudahKumpulProjek($idTentor, $idSiswa)) {
            return redirect()
                ->route('tentor.sertifikat')
                ->withErrors(['id_siswa' => 'Siswa ini belum mengumpulkan tugas besar (projek). Sertifikat hanya untuk siswa yang sudah mengerjakan tugas besar.']);
        }

        $namaSimpan = $this->simpanFile($request->file('file_sertifikat'));

        try {
            DB::transaction(function () use ($request, $idSiswa, $namaSimpan, $idTentor) {
                DB::statement('LOCK TABLE sertifikat IN SHARE ROW EXCLUSIVE MODE');

                DB::table('sertifikat')->insert([
                    'id_sertifikat'       => (int) DB::table('sertifikat')->max('id_sertifikat') + 1,
                    'id_siswa'            => $idSiswa,
                    'id_tentor'           => $idTentor,
                    'nama_projek'         => $request->nama_ujian,
                    'tanggal_pengumpulan' => $request->tanggal_ujian,
                    'file_sertifikat'     => $namaSimpan,
                    'tanggal_upload'      => now(),
                ]);
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete("sertifikat/{$namaSimpan}");
            throw $e;
        }

        return redirect()->route('tentor.sertifikat')->with('sukses', 'Sertifikat berhasil diupload.');
    }

    public function update(Request $request, $idSertifikat)
    {
        $request->validate([
            'nama_ujian'      => 'required|string|max:150',
            'tanggal_ujian'   => 'required|date',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], $this->pesan());

        $sertifikat = DB::table('sertifikat')
            ->where('id_sertifikat', $idSertifikat)
            ->where('id_tentor', auth()->id())
            ->first();

        abort_if(!$sertifikat, 404);

        $ubah = [
            'nama_projek'         => $request->nama_ujian,
            'tanggal_pengumpulan' => $request->tanggal_ujian,
        ];

        $fileBaru = null;
        if ($request->hasFile('file_sertifikat')) {
            $fileBaru = $this->simpanFile($request->file('file_sertifikat'));
            $ubah['file_sertifikat'] = $fileBaru;
        }

        try {
            DB::table('sertifikat')->where('id_sertifikat', $idSertifikat)->update($ubah);
        } catch (\Throwable $e) {
            if ($fileBaru) {
                Storage::disk('public')->delete("sertifikat/{$fileBaru}");
            }
            throw $e;
        }

        if ($fileBaru) {
            Storage::disk('public')->delete("sertifikat/{$sertifikat->file_sertifikat}");
        }

        return redirect()->route('tentor.sertifikat')->with('sukses', 'Perubahan sertifikat berhasil disimpan.');
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

    private function siswaLayakSertifikat($idTentor)
    {
        return DB::table('pengumpulan_tugas as pt')
            ->join('tugas as t', 'pt.id_tugas', '=', 't.id_tugas')
            ->join('users as u', 'pt.id_siswa', '=', 'u.id_user')
            ->where('t.id_tentor', $idTentor)
            ->where('t.tipe', 'projek')
            ->distinct()
            ->orderBy('u.nama')
            ->select('u.id_user', 'u.nama')
            ->get();
    }

    private function sudahKumpulProjek(int $idTentor, int $idSiswa): bool
    {
        return DB::table('pengumpulan_tugas as pt')
            ->join('tugas as t', 'pt.id_tugas', '=', 't.id_tugas')
            ->where('t.id_tentor', $idTentor)
            ->where('t.tipe', 'projek')
            ->where('pt.id_siswa', $idSiswa)
            ->exists();
    }

    private function simpanFile($file): string
    {
        $namaSimpan = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '.' . strtolower($file->getClientOriginalExtension());
        $file->storeAs('sertifikat', $namaSimpan, 'public');

        return $namaSimpan;
    }

    private function pesan(): array
    {
        return [
            'id_siswa.required'        => 'Pilih siswa terlebih dahulu.',
            'nama_ujian.required'      => 'Nama projek wajib diisi.',
            'tanggal_ujian.required'   => 'Tanggal pengumpulan wajib diisi.',
            'file_sertifikat.required' => 'File sertifikat wajib dipilih.',
            'file_sertifikat.mimes'    => 'Format file harus PDF, JPG, JPEG, atau PNG.',
            'file_sertifikat.max'      => 'Ukuran file maksimal 5 MB.',
        ];
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