<?php
// Controller Monitoring Tugas Operator - Dhuo Creative

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Kursus;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $siswaList = User::where('role', 'siswa')->orderBy('nama', 'asc')->get();

        $siswa = null;
        if ($request->filled('id_siswa')) {
            $siswa = User::where('id_user', $request->id_siswa)->where('role', 'siswa')->first();
        } elseif ($request->filled('siswa')) {
            $siswa = User::where('nama', $request->siswa)->where('role', 'siswa')->first();
        }

        if (!$siswa && $siswaList->isNotEmpty()) {
            $siswa = $siswaList->first();
        }

        $tugasList = collect();
        if ($siswa) {
            $kelasIds = Kelas::where('id_siswa', $siswa->id_user)->pluck('id_kelas');
            $tugasList = Tugas::whereIn('id_kelas', $kelasIds)
                ->with(['kelas.kursus', 'tentor', 'pengumpulanTugas' => function ($q) use ($siswa) {
                    $q->where('id_siswa', $siswa->id_user)->with('nilai');
                }])
                ->orderBy('deadline', 'desc')
                ->get();
        }

        $tentorList = User::where('role', 'tentor')->orderBy('nama', 'asc')->get();
        $kursusList = Kursus::orderBy('nama_kursus', 'asc')->get();

        return view('operator.tugas', compact('siswa', 'siswaList', 'tugasList', 'tentorList', 'kursusList'));
    }

    public function downloadFile(Request $request)
    {
        $file = $request->query('file');
        if (!$file || $file === '-') {
            return redirect()->back()->with('error', 'File tugas tidak tersedia.');
        }

        $filePath = storage_path('app/public/' . $file);
        if (!file_exists($filePath)) {
            $dir = dirname($filePath);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($filePath, "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj 3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000052 00000 n \n0000000101 00000 n \ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n178\n%%EOF");
        }

        if ($request->has('download')) {
            return response()->download($filePath, basename($file));
        }

        $mime = mime_content_type($filePath) ?: 'application/pdf';
        return response()->file($filePath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . basename($file) . '"'
        ]);
    }
}
