<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PengumpulanTugasController extends Controller
{
    private const PER_PAGE = 5;

    public function index()
    {
        $idSiswa = auth()->id();

        $kelasSiswa = DB::table('kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->where('kelas.id_siswa', $idSiswa)
            ->where('kelas.status', 'aktif')
            ->orderBy('kursus.nama_kursus')
            ->select('kelas.id_kelas', 'kursus.nama_kursus')
            ->get();

        $semuaTugas = DB::table('tugas')
            ->join('kelas', 'tugas.id_kelas', '=', 'kelas.id_kelas')
            ->leftJoin('pengumpulan_tugas as p', function ($j) {
                $j->on('p.id_tugas', '=', 'tugas.id_tugas')
                    ->on('p.id_siswa', '=', 'kelas.id_siswa');
            })
            ->leftJoin('nilai', 'nilai.id_pengumpulan', '=', 'p.id_pengumpulan')
            ->where('kelas.id_siswa', $idSiswa)
            ->orderBy('tugas.deadline')
            ->select(
                'tugas.id_tugas',
                'tugas.judul',
                'kelas.id_kelas',
                'nilai.nilai'
            )
            ->get()
            ->map(function ($t) {
                $t->sudah_dinilai = $t->nilai !== null;
                return $t;
            });

        $tugas = DB::table('tugas')
            ->join('kelas', 'tugas.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->leftJoin('pengumpulan_tugas as p', function ($j) {
                $j->on('p.id_tugas', '=', 'tugas.id_tugas')
                    ->on('p.id_siswa', '=', 'kelas.id_siswa');
            })
            ->leftJoin('nilai', 'nilai.id_pengumpulan', '=', 'p.id_pengumpulan')
            ->where('kelas.id_siswa', $idSiswa)
            ->orderByDesc('tugas.deadline')
            ->select(
                'tugas.id_tugas',
                'tugas.judul',
                'tugas.deskripsi',
                'tugas.deadline',
                'kelas.id_kelas',
                'kursus.nama_kursus',
                'p.status as status_pengumpulan',
                'nilai.nilai'
            )
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $tugas->setCollection(
            $tugas->getCollection()->map(function ($t) {
                $d = Carbon::parse($t->deadline)->locale('id');

                $t->deadline_tanggal = $d->translatedFormat('j F Y');
                $t->deadline_jam = $d->format('H:i');
                $t->deskripsi_teks = $t->deskripsi
                    ? \Illuminate\Support\Str::limit(strip_tags($t->deskripsi), 120)
                    : '—';

                [$t->status_teks, $t->status_css] = match ($t->status_pengumpulan) {
                    'terkumpul' => ['Dikumpulkan', 'pt-status-done'],
                    'terlambat' => ['Terlambat', 'pt-status-late'],
                    default => ['Belum Dikumpulkan', 'pt-status-pending'],
                };

                $t->sudah_dinilai = $t->nilai !== null;

                return $t;
            })
        );

        return view('siswa.pengumpulanTugas', compact('tugas', 'kelasSiswa', 'semuaTugas'));
    }

    public function kumpulkan(Request $request)
    {
        $idSiswa = auth()->id();

        $request->validate([
            'id_tugas' => 'required|integer',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,jpg,jpeg,png|max:51200',
        ], [
            'id_tugas.required' => 'Pilih tugas terlebih dahulu.',
            'file.required' => 'Pilih file tugas terlebih dahulu.',
            'file.mimes' => 'File harus berjenis pdf, doc, docx, ppt, pptx, zip, jpg, jpeg, atau png.',
            'file.max' => 'Ukuran file maksimal 50 MB.',
        ]);

        $tugas = DB::table('tugas')
            ->join('kelas', 'tugas.id_kelas', '=', 'kelas.id_kelas')
            ->where('tugas.id_tugas', $request->id_tugas)
            ->where('kelas.id_siswa', $idSiswa)
            ->select('tugas.id_tugas', 'tugas.deadline')
            ->first();

        abort_if(!$tugas, 404);

        $lama = DB::table('pengumpulan_tugas')
            ->where('id_tugas', $tugas->id_tugas)
            ->where('id_siswa', $idSiswa)
            ->first();

        if (
            $lama &&
            DB::table('nilai')
                ->where('id_pengumpulan', $lama->id_pengumpulan)
                ->exists()
        ) {
            return back()->withErrors([
                'id_tugas' => 'Tugas ini sudah dinilai, jadi tidak bisa dikumpulkan ulang.'
            ]);
        }

        $file = $request->file('file');

        $ext = strtolower($file->getClientOriginalExtension());
        $nama = "tugas_{$tugas->id_tugas}_siswa_{$idSiswa}.{$ext}";
        $path = "jawaban/{$nama}";

        $status = now()->gt(Carbon::parse($tugas->deadline))
            ? 'terlambat'
            : 'terkumpul';

        Storage::disk('public')->putFileAs('jawaban', $file, $nama);

        DB::transaction(function () use ($lama, $tugas, $idSiswa, $path, $status) {

            if ($lama) {

                if ($lama->file_jawaban !== $path) {
                    Storage::disk('public')->delete($lama->file_jawaban);
                }

                DB::table('pengumpulan_tugas')
                    ->where('id_pengumpulan', $lama->id_pengumpulan)
                    ->update([
                        'file_jawaban' => $path,
                        'waktu_pengumpulan' => now(),
                        'status' => $status,
                    ]);

            } else {

                DB::table('pengumpulan_tugas')->insert([
                    'id_pengumpulan' =>
                        ((int) DB::table('pengumpulan_tugas')->max('id_pengumpulan')) + 1,
                    'id_tugas' => $tugas->id_tugas,
                    'id_siswa' => $idSiswa,
                    'file_jawaban' => $path,
                    'waktu_pengumpulan' => now(),
                    'status' => $status,
                ]);

            }
        });

        return redirect()
            ->route('siswa.pengumpulanTugas')
            ->with(
                'sukses',
                $status === 'terlambat'
                    ? 'Tugas berhasil dikumpulkan (terlambat).'
                    : 'Tugas berhasil dikumpulkan.'
            );
    }
}