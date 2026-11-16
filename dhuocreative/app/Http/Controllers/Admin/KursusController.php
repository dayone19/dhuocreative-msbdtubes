<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KursusController extends Controller
{
    // nampilin semua kursus
    public function index(Request $request)
    {
        // Ambil seluruh data kursus
        $queryKursus = DB::table('kursus')
            ->select(
                'id_kursus',
                'nama_kursus',
                'deskripsi',
                'durasi',
                'status',
                'harga'
            );

        if ($request->filled('cari_kursus')) {
            $queryKursus->where(
                'nama_kursus',
                'ILIKE',
                '%' . $request->cari_kursus . '%'
            );
        }

        $dataKursus = $queryKursus
            ->orderBy('nama_kursus')
            ->paginate(5)
            ->withQueryString();

        return view('admin.manajemenKursus', compact(
            'dataKursus'
        ));
    }

    // tambah kursus
    public function store(Request $request)
    {
        $request->validate([
            'nama_kursus' => 'required|string|max:100|unique:kursus,nama_kursus',
            'deskripsi' => 'nullable|string',
            'durasi' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        // Tambahkan kursus baru
        DB::table('kursus')->insert([
            'nama_kursus' => $request->nama_kursus,
            'deskripsi' => $request->deskripsi,
            'durasi' => $request->durasi,
            'status' => $request->status,
            'harga' => $request->harga,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()
            ->route('admin.manajemenKursus')
            ->with('success', 'Kursus berhasil ditambahkan!');
    }

    // edit kursus
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kursus' => 'required|string|max:100|unique:kursus,nama_kursus,' . $id . ',id_kursus',
            'deskripsi' => 'nullable|string',
            'durasi' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        // Perbarui data kursus berdasarkan ID
        DB::table('kursus')
            ->where('id_kursus', $id)
            ->update([
                'nama_kursus' => $request->nama_kursus,
                'deskripsi' => $request->deskripsi,
                'durasi' => $request->durasi,
                'harga' => $request->harga,
                'status' => $request->status,
                'updated_at' => now()
            ]);

        return redirect()
            ->route('admin.manajemenKursus')
            ->with('success', 'Kursus berhasil diperbarui!');
    }

    // nonaktif kan kursus
    public function nonaktifkan($id)
    {
        // Ubah status kursus menjadi nonaktif
        DB::table('kursus')
            ->where('id_kursus', $id)
            ->update([
                'status' => 'nonaktif',
                'updated_at' => now()
            ]);

        return redirect()
            ->route('admin.manajemenKursus')
            ->with('success', 'Kursus berhasil dinonaktifkan!');
    }

    // aktifkan lagi
    public function aktifkan($id)
    {
        DB::table('kursus')->where('id_kursus', $id)->update([
            'status' => 'aktif',
            'updated_at' => now()
        ]);

        return redirect()->route('admin.manajemenKursus')
            ->with('success', 'Kursus berhasil diaktifkan kembali!');
    }
}
