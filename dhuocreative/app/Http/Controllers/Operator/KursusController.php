<?php
// Controller Kursus Operator - Dhuo Creative

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kursus;

class KursusController extends Controller
{
    public function index(Request $request)
    {
        $kursusList = Kursus::orderBy('id_kursus', 'desc')->get();
        $kelasList  = \App\Models\Kelas::with('kursus')->orderBy('id_kelas', 'asc')->get();
        return view('operator.kursus', compact('kursusList', 'kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kursus' => 'required|string|max:100|unique:kursus,nama_kursus',
            'deskripsi'   => 'nullable|string',
            'durasi'      => 'required|integer|min:1',
            'status'      => 'required|in:aktif,nonaktif',
        ], [
            'nama_kursus.required' => 'Nama kursus wajib diisi.',
            'nama_kursus.unique'   => 'Nama kursus sudah terdaftar, silakan gunakan nama lain.',
            'durasi.required'      => 'Durasi pertemuan wajib diisi.',
            'status.required'      => 'Status kursus wajib dipilih.',
        ]);

        try {
            $nextId = (Kursus::max('id_kursus') ?? 0) + 1;

            $kursus = Kursus::create([
                'id_kursus'        => $nextId,
                'nama_kursus'      => $request->nama_kursus,
                'deskripsi'        => $request->deskripsi ?? 'Program kursus di Dhuo Creative.',
                'durasi'           => (int) $request->durasi,
                'jumlah_pertemuan' => (int) $request->durasi,
                'harga'            => 2500000,
                'status'           => $request->status,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Kursus {$request->nama_kursus} berhasil ditambahkan!",
                    'kursus'  => $kursus
                ]);
            }

            return redirect()->route('operator.kursus')->with('success', "Kursus {$request->nama_kursus} berhasil ditambahkan!");
        } catch (\Throwable $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', 'Gagal menambahkan kursus: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $kursus = Kursus::where('id_kursus', $id)->firstOrFail();

        $request->validate([
            'nama_kursus' => 'required|string|max:100|unique:kursus,nama_kursus,' . $kursus->id_kursus . ',id_kursus',
            'deskripsi'   => 'nullable|string',
            'durasi'      => 'required|integer|min:1',
            'status'      => 'required|in:aktif,nonaktif',
        ], [
            'nama_kursus.required' => 'Nama kursus wajib diisi.',
            'nama_kursus.unique'   => 'Nama kursus sudah terdaftar, silakan gunakan nama lain.',
            'durasi.required'      => 'Durasi pertemuan wajib diisi.',
            'status.required'      => 'Status kursus wajib dipilih.',
        ]);

        try {
            $kursus->update([
                'nama_kursus' => $request->nama_kursus,
                'deskripsi'   => $request->deskripsi,
                'durasi'      => (int) $request->durasi,
                'status'      => $request->status,
                'updated_at'  => now(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Perubahan data kursus {$request->nama_kursus} berhasil disimpan!",
                    'kursus'  => $kursus
                ]);
            }

            return redirect()->route('operator.kursus')->with('success', "Perubahan data kursus {$request->nama_kursus} berhasil disimpan!");
        } catch (\Throwable $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', 'Gagal memperbarui kursus: ' . $e->getMessage())->withInput();
        }
    }
}
