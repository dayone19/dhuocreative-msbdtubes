<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    private const PER_PAGE = 5;

    public function index()
    {
        $idSiswa = auth()->id();

        $allRows = DB::table('tugas')
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
                'tugas.judul',
                'kursus.nama_kursus',
                'nilai.nilai',
                'nilai.tanggal_nilai'
            )
            ->get();

        $skorTerisi = $allRows->pluck('nilai')
            ->filter(fn($n) => $n !== null)
            ->map(fn($n) => (float) $n);

        $rataRata = $skorTerisi->isNotEmpty()
            ? $this->angka($skorTerisi->avg(), 1)
            : '—';

        $tertinggi = $skorTerisi->isNotEmpty()
            ? $this->angka($skorTerisi->max())
            : '—';

        $dinilai = $skorTerisi->count();
        $totalTugas = $allRows->count();

        $rows = DB::table('tugas')
            ->join('kelas', 'tugas.id_kelas', '=', 'kelas.id_kelas')
            ->join('kursus', 'kelas.id_kursus', '=', 'kursus.id_kursus')
            ->leftJoin('pengumpulan_tugas as p', function ($j) {
                $j->on('p.id_tugas', '=', 'tugas.id_tugas')
                    ->on('p.id_siswa', '=', 'kelas.id_siswa');
            })
            ->leftJoin('nilai', 'nilai.id_pengumpulan', '=', 'p.id_pengumpulan')
            ->leftJoin('users as tentor', 'tentor.id_user', '=', 'nilai.id_tentor')
            ->where('kelas.id_siswa', $idSiswa)
            ->orderByDesc('tugas.deadline')
            ->select(
                'tugas.judul',
                'kursus.nama_kursus',
                'nilai.nilai',
                'nilai.tanggal_nilai',
                'nilai.feedback',
                'tentor.nama as nama_tentor'
            )
            ->paginate(self::PER_PAGE);

        $daftar = $rows->getCollection()->map(function ($r) {

            $skor = $r->nilai !== null ? (float) $r->nilai : null;

            [$label, $kelasCss] = match (true) {
                $skor === null => ['Menunggu', 'nl-badge-wait'],
                $skor >= 90    => ['Sangat Baik', 'nl-badge-great'],
                $skor >= 80    => ['Baik', 'nl-badge-good'],
                $skor >= 70    => ['Cukup Baik', 'nl-badge-ok'],
                default        => ['Perlu Ditingkatkan', 'nl-badge-low'],
            };

            $feedback = is_string($r->feedback) ? trim($r->feedback) : '';
            $punyaFeedback = $skor !== null && $feedback !== '';

            $inisial = 'K';
            if (!empty($r->nama_tentor)) {
                $parts = preg_split('/\s+/', trim($r->nama_tentor));
                $inisial = strtoupper(mb_substr($parts[0], 0, 1));
            }

            return (object) [
                'judul'         => $r->judul,
                'kursus'        => $r->nama_kursus,
                'dinilai'       => $skor !== null,
                'tanggal'       => $r->tanggal_nilai
                    ? Carbon::parse($r->tanggal_nilai)
                        ->locale('id')
                        ->translatedFormat('j F Y')
                    : 'Belum dinilai',
                'nilai'         => $skor !== null
                    ? $this->angka($skor)
                    : null,
                'label'         => $label,
                'css'           => $kelasCss,
                'feedback'      => $punyaFeedback ? $feedback : null,
                'nama_tentor'   => $r->nama_tentor ?: 'Kak Dhuo',
                'inisial'       => $inisial,
            ];
        });

        $rows->setCollection($daftar);

        return view('siswa.nilai', [
            'daftar' => $rows,
            'rataRata' => $rataRata,
            'tertinggi' => $tertinggi,
            'dinilai' => $dinilai,
            'totalTugas' => $totalTugas,
        ]);
    }

    private function angka(float $n, int $maxDesimal = 2): string
    {
        return rtrim(
            rtrim(number_format($n, $maxDesimal, ',', '.'), '0'),
            ','
        );
    }
}
