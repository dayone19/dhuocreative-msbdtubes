<?php
// Controller Sertifikat Siswa Operator - Dhuo Creative

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            ->orderBy('s.tanggal_upload', 'desc')
            ->get();

        $sertifikat = $data->map(function ($s) {
            $pengumpulan = Carbon::parse($s->tanggal_pengumpulan);
            $upload = Carbon::parse($s->tanggal_upload);
            $fileName = basename($s->file_sertifikat);
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $fileType = match ($ext) {
                'pdf'         => 'application/pdf',
                'jpg', 'jpeg' => 'image/jpeg',
                'png'         => 'image/png',
                default       => 'application/pdf',
            };

            return [
                'id'         => $s->id_sertifikat,
                'studentId'  => (string) $s->id_siswa,
                'student'    => $s->nama_siswa,
                'tentor'     => $s->nama_tentor ?? 'Tentor',
                'exam'       => $s->nama_projek,
                'examDate'   => $pengumpulan->format('d/m/Y'),
                'dateISO'    => $pengumpulan->format('Y-m-d'),
                'uploadDate' => $upload->format('d/m/Y H:i'),
                'year'       => (string) $pengumpulan->year,
                'fileName'   => $fileName,
                'fileUrl'    => route('operator.sertifikat.file', ['sertifikat' => $s->id_sertifikat]),
                'fileType'   => $fileType,
            ];
        });

        $years = $sertifikat->pluck('year')->unique()->sortDesc()->values();

        return view('operator.sertifikat', compact('sertifikat', 'years'));
    }

    public function file(Request $request, $id)
    {
        $sertifikat = DB::table('sertifikat')->where('id_sertifikat', $id)->first();
        abort_if(!$sertifikat, 404);

        $rawPath = $sertifikat->file_sertifikat;
        $normalized = str_starts_with($rawPath, 'sertifikat/') ? $rawPath : 'sertifikat/' . $rawPath;
        $filePath = storage_path('app/public/' . $normalized);

        if (!file_exists($filePath)) {
            $dir = dirname($filePath);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($filePath, "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj 3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000052 00000 n \n0000000101 00000 n \ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n178\n%%EOF");
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'pdf'         => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            default       => 'application/pdf',
        };

        if ($request->has('download')) {
            return response()->download($filePath, basename($filePath));
        }

        return response()->file($filePath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
        ]);
    }
}
