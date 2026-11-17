<?php
// Controller Pengumuman Operator - Dhuo Creative

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengumumanController extends Controller
{
    public function index()
    {
        $daftarPengumuman = DB::table('pengumuman as p')
            ->join('users as u', 'p.id_user', '=', 'u.id_user')
            ->select(
                'p.id_pengumuman',
                'p.id_user',
                'p.judul',
                'p.isi',
                'p.tanggal',
                'u.nama as nama_pembuat',
                'u.role as role_pembuat'
            )
            ->orderBy('p.id_pengumuman', 'asc')
            ->get()
            ->map(function ($p) {
                $tgl = Carbon::parse($p->tanggal);
                $p->tanggal_iso  = $tgl->format('Y-m-d');
                $p->tanggal_teks = $tgl->locale('id')->translatedFormat('d F Y');
                return $p;
            });

        return view('operator.pengumuman', compact('daftarPengumuman'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'   => 'required|string|max:150',
            'isi'     => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $userId = auth()->id() ?? 2;
        $nextId = (DB::table('pengumuman')->max('id_pengumuman') ?? 0) + 1;

        DB::table('pengumuman')->insert([
            'id_pengumuman' => $nextId,
            'id_user'       => $userId,
            'judul'         => $validated['judul'],
            'isi'           => $validated['isi'],
            'tanggal'       => $validated['tanggal'],
        ]);

        return redirect()
            ->route('operator.pengumuman')
            ->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    public function update(Request $request, $id)
    {
        $pengumuman = DB::table('pengumuman')->where('id_pengumuman', $id)->first();
        abort_if(!$pengumuman, 404);

        $validated = $request->validate([
            'judul'   => 'required|string|max:150',
            'isi'     => 'required|string',
            'tanggal' => 'required|date',
        ]);

        DB::table('pengumuman')
            ->where('id_pengumuman', $id)
            ->update([
                'judul'   => $validated['judul'],
                'isi'     => $validated['isi'],
                'tanggal' => $validated['tanggal'],
            ]);

        return redirect()
            ->route('operator.pengumuman')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengumuman = DB::table('pengumuman')->where('id_pengumuman', $id)->first();
        abort_if(!$pengumuman, 404);

        DB::table('pengumuman')
            ->where('id_pengumuman', $id)
            ->delete();

        return redirect()
            ->route('operator.pengumuman')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
