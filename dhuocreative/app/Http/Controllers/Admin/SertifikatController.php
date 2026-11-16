<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function index()
    {
        $daftarSertifikat = DB::table('sertifikat as s')
            ->join('users as u', 's.id_siswa', '=', 'u.id_user')
            ->select(
                's.id_sertifikat',
                's.nama_projek',
                's.tanggal_pengumpulan',
                's.file_sertifikat',
                's.tanggal_upload',
                'u.nama as nama_siswa'
            )
            ->orderByDesc('s.tanggal_upload')
            ->get()
            ->map(function ($item) {
                $item->tanggal_pengumpulan_label = $item->tanggal_pengumpulan
                    ? date('d/m/Y', strtotime($item->tanggal_pengumpulan))
                    : '-';

                $item->tanggal_upload_label = $item->tanggal_upload
                    ? date('d/m/Y H:i', strtotime($item->tanggal_upload))
                    : '-';

                $item->tahun = $item->tanggal_pengumpulan
                    ? date('Y', strtotime($item->tanggal_pengumpulan))
                    : null;

                $item->file_name = $item->file_sertifikat
                    ? basename($item->file_sertifikat)
                    : null;

                $item->file_url = $item->file_sertifikat
                    ? Storage::url($item->file_sertifikat)
                    : null;

                $extension = strtolower(
                    pathinfo($item->file_sertifikat ?? '', PATHINFO_EXTENSION)
                );

                $item->file_type = match ($extension) {
                    'pdf' => 'application/pdf',
                    'jpg', 'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    default => null,
                };

                return $item;
            });

        $daftarTahun = $daftarSertifikat
            ->pluck('tahun')
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        return view('admin.nilaiSertifikat', compact(
            'daftarSertifikat',
            'daftarTahun'
        ));
    }
}
