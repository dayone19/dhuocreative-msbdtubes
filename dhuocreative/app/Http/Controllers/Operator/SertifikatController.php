<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Throwable;

class SertifikatController extends Controller
{
    public function index()
    {
        $data = DB::table('sertifikat as s')
            ->join('users as u', 's.id_siswa', '=', 'u.id_user')
            ->leftJoin('users as t', 's.id_tentor', '=', 't.id_user')
            ->select(
                's.id_sertifikat',
                's.id_siswa',
                'u.nama as nama_siswa',
                't.nama as nama_tentor',
                's.nama_projek',
                's.tanggal_pengumpulan',
                's.file_sertifikat',
                's.tanggal_upload'
            )
            ->orderByDesc('s.tanggal_upload')
            ->get();

        $sertifikat = $data->map(function ($s) {
            $pengumpulan = Carbon::parse($s->tanggal_pengumpulan);
            $upload = Carbon::parse($s->tanggal_upload);

            $fileName = basename($s->file_sertifikat);
            $namaTampil = preg_replace('/^\d+\_/', '', $fileName);
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $fileType = match ($ext) {
                'pdf' => 'application/pdf',
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                default => 'application/octet-stream',
            };

            return [
                'id' => $s->id_sertifikat,
                'studentId' => (string) $s->id_siswa,
                'student' => $s->nama_siswa,
                'tentor' => $s->nama_tentor ?? 'Tentor',
                'exam' => $s->nama_projek,
                'examDate' => $pengumpulan->format('d/m/Y'),
                'dateISO' => $pengumpulan->format('Y-m-d'),
                'uploadDate' => $upload->format('d/m/Y H:i'),
                'year' => (string) $pengumpulan->year,
                'fileName' => $namaTampil,
                'fileUrl' => route('operator.sertifikat.file', [
                    'sertifikat' => $s->id_sertifikat
                ]),
                'fileType' => $fileType,
            ];
        });

        $years = $sertifikat
            ->pluck('year')
            ->unique()
            ->sortDesc()
            ->values();

        $siswa = $this->siswaLayakSertifikat();

        return view('operator.sertifikat', compact(
            'sertifikat',
            'years',
            'siswa'
        ));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|integer|exists:users,id_user',
            'nama_ujian' => 'required|string|max:150',
            'tanggal_ujian' => 'required|date',
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], $this->pesan());

        $idSiswa = (int) $request->id_siswa;

        if (!$this->sudahKumpulProjek($idSiswa)) {
            return redirect()
                ->route('operator.sertifikat')
                ->withErrors([
                    'id_siswa' => 'Siswa ini belum mengumpulkan tugas besar (projek). Sertifikat hanya dapat diberikan kepada siswa yang sudah mengumpulkan projek.'
                ])
                ->withInput();
        }

        $idTentor = $this->tentorProjekSiswa($idSiswa);

        if (!$idTentor) {
            return redirect()
                ->route('operator.sertifikat')
                ->withErrors([
                    'id_siswa' => 'Tentor untuk projek siswa ini tidak ditemukan.'
                ])
                ->withInput();
        }

        $namaSimpan = $this->simpanFile(
            $request->file('file_sertifikat')
        );

        try {
            DB::transaction(function () use (
                $request,
                $idSiswa,
                $idTentor,
                $namaSimpan
            ) {
                DB::statement(
                    'LOCK TABLE sertifikat IN SHARE ROW EXCLUSIVE MODE'
                );

                $idSertifikat = (int) DB::table('sertifikat')
                    ->max('id_sertifikat') + 1;

                DB::table('sertifikat')->insert([
                    'id_sertifikat' => $idSertifikat,
                    'id_siswa' => $idSiswa,
                    'id_tentor' => $idTentor,
                    'nama_projek' => $request->nama_ujian,
                    'tanggal_pengumpulan' => $request->tanggal_ujian,
                    'file_sertifikat' => $namaSimpan,
                    'tanggal_upload' => now(),
                ]);
            });
        } catch (Throwable $e) {
            Storage::disk('public')->delete('sertifikat/' . $namaSimpan);

            throw $e;
        }

        return redirect()
            ->route('operator.sertifikat')
            ->with('success', 'Sertifikat berhasil diupload.');
    }

    public function update(Request $request, $idSertifikat)
    {
        $request->validate([
            'nama_ujian' => 'required|string|max:150',
            'tanggal_ujian' => 'required|date',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], $this->pesan());

        $sertifikat = DB::table('sertifikat')
            ->where('id_sertifikat', $idSertifikat)
            ->first();

        abort_if(!$sertifikat, 404);

        $namaLama = basename($sertifikat->file_sertifikat);
        $namaBaru = null;

        if ($request->hasFile('file_sertifikat')) {
            $namaBaru = $this->simpanFile(
                $request->file('file_sertifikat')
            );
        }

        try {
            $dataUpdate = [
                'nama_projek' => $request->nama_ujian,
                'tanggal_pengumpulan' => $request->tanggal_ujian,
            ];

            if ($namaBaru) {
                $dataUpdate['file_sertifikat'] = $namaBaru;
            }

            DB::table('sertifikat')
                ->where('id_sertifikat', $idSertifikat)
                ->update($dataUpdate);

        } catch (Throwable $e) {
            if ($namaBaru) {
                Storage::disk('public')->delete(
                    'sertifikat/' . $namaBaru
                );
            }

            throw $e;
        }

        if ($namaBaru) {
            Storage::disk('public')->delete(
                'sertifikat/' . $namaLama
            );
        }

        return redirect()
            ->route('operator.sertifikat')
            ->with('success', 'Sertifikat berhasil diperbarui.');
    }

    public function file(Request $request, $idSertifikat)
    {
        $sertifikat = DB::table('sertifikat')
            ->where('id_sertifikat', $idSertifikat)
            ->first();

        abort_if(!$sertifikat, 404);

        $namaFile = basename($sertifikat->file_sertifikat);
        $path = 'sertifikat/' . $namaFile;

        abort_unless(
            Storage::disk('public')->exists($path),
            404,
            'File sertifikat tidak ditemukan.'
        );

        $namaTampil = preg_replace('/^\d+\_/', '', $namaFile);

        if ($request->has('download')) {
            return Storage::disk('public')->download(
                $path,
                $namaTampil
            );
        }

        return Storage::disk('public')->response(
            $path,
            $namaTampil
        );
    }

    private function siswaLayakSertifikat()
    {
        return DB::table('pengumpulan_tugas as pt')
            ->join('tugas as t', 'pt.id_tugas', '=', 't.id_tugas')
            ->join('users as u', 'pt.id_siswa', '=', 'u.id_user')
            ->where('t.tipe', 'projek')
            ->distinct()
            ->orderBy('u.nama')
            ->select(
                'u.id_user',
                'u.nama'
            )
            ->get();
    }

    private function sudahKumpulProjek(int $idSiswa): bool
    {
        return DB::table('pengumpulan_tugas as pt')
            ->join('tugas as t', 'pt.id_tugas', '=', 't.id_tugas')
            ->where('t.tipe', 'projek')
            ->where('pt.id_siswa', $idSiswa)
            ->exists();
    }

    private function tentorProjekSiswa(int $idSiswa)
    {
        return DB::table('pengumpulan_tugas as pt')
            ->join('tugas as t', 'pt.id_tugas', '=', 't.id_tugas')
            ->where('t.tipe', 'projek')
            ->where('pt.id_siswa', $idSiswa)
            ->value('t.id_tentor');
    }

    private function simpanFile($file): string
    {
        $namaSimpan = time()
            . '_'
            . Str::slug(
                pathinfo(
                    $file->getClientOriginalName(),
                    PATHINFO_FILENAME
                )
            )
            . '.'
            . strtolower($file->getClientOriginalExtension());

        $file->storeAs(
            'sertifikat',
            $namaSimpan,
            'public'
        );

        return $namaSimpan;
    }

    private function pesan(): array
    {
        return [
            'id_siswa.required' => 'Nama siswa wajib dipilih.',
            'id_siswa.integer' => 'Pilihan siswa tidak valid.',
            'id_siswa.exists' => 'Data siswa tidak ditemukan.',
            'nama_ujian.required' => 'Nama projek wajib diisi.',
            'nama_ujian.max' => 'Nama projek maksimal 150 karakter.',
            'tanggal_ujian.required' => 'Tanggal pengumpulan wajib diisi.',
            'tanggal_ujian.date' => 'Format tanggal tidak valid.',
            'file_sertifikat.required' => 'File sertifikat wajib diupload.',
            'file_sertifikat.file' => 'File sertifikat tidak valid.',
            'file_sertifikat.mimes' => 'File harus berformat PDF, JPG, JPEG, atau PNG.',
            'file_sertifikat.max' => 'Ukuran file maksimal 5 MB.',
        ];
    }
}
