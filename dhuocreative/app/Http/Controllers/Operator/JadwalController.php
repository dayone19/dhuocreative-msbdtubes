<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Kursus;
use App\Models\User;

class JadwalController extends Controller
{
    public function index()
    {
        // 1. Ambil jadwal beserta relasi kelas.kursus, kelas.siswa, dan tentor
        $jadwals = Jadwal::with(['kelas.kursus', 'kelas.siswa', 'tentor'])->get();

        // 2. Logika Ringkasan Mingguan (Senin s/d Sabtu)
        $hariList = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $jadwalMingguan = [];
        foreach ($hariList as $isoDay => $namaHari) {
            $jadwalHari = $jadwals->first(function ($j) use ($isoDay) {
                return \Carbon\Carbon::parse($j->tanggal)->dayOfWeekIso == $isoDay && $j->status == 'terjadwal';
            });

            $jadwalMingguan[$namaHari] = $jadwalHari;
        }

        // 3. Data master untuk dropdown modal tambah jadwal
        $kursusList = Kursus::all();
        $kelasList  = Kelas::with(['kursus', 'siswa'])->get();
        $tentorList = User::where('role', 'tentor')->get();
        $siswaList  = User::where('role', 'siswa')->get();

        return view('operator.jadwal', compact(
            'jadwals',
            'jadwalMingguan',
            'kursusList',
            'kelasList',
            'tentorList',
            'siswaList'
        ));
    }
    public function store(Request $request)
    {
        $request->validate([
            'kursus'      => 'required',
            'kelas'       => 'required',
            'tentor'      => 'required',
            'siswa'       => 'required',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'status'      => 'required',
        ]);

        $tentor = User::where('nama', $request->tentor)->where('role', 'tentor')->first();
        $siswa  = User::where('nama', $request->siswa)->where('role', 'siswa')->first();
        $kursus = Kursus::where('nama_kursus', $request->kursus)->first();

        // Cari atau buat kelas jika belum ada
        $kelas = Kelas::where('id_kursus', $kursus->id_kursus)
                      ->where('kelas', strtolower($request->kelas))
                      ->where('id_siswa', $siswa->id_user)
                      ->first();

        if (!$kelas) {
            $nextIdKelas = (Kelas::max('id_kelas') ?? 0) + 1;
            $kelas = Kelas::create([
                'id_kelas'   => $nextIdKelas,
                'id_kursus'  => $kursus->id_kursus,
                'status'     => 'aktif',
                'kelas'      => strtolower($request->kelas),
                'id_siswa'   => $siswa->id_user,
            ]);
        }

              // 1. Cek apakah ini EDIT jadwal lama (jika ada id_jadwal)
        if ($request->filled('id_jadwal')) {
            $jadwal = Jadwal::find($request->id_jadwal);
            if ($jadwal) {
                $jadwal->update([
                    'id_kelas'    => $kelas->id_kelas,
                    'id_tentor'   => $tentor->id_user,
                    'tanggal'     => $request->tanggal,
                    'jam_mulai'   => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'status'      => $request->status,
                ]);

                return redirect()->route('operator.jadwal')->with('success', 'Jadwal berhasil diperbarui!');
            }
        }

        // 2. Jika bukan edit (id_jadwal kosong), berarti SIMPAN JADWAL BARU
        $nextIdJadwal = (Jadwal::max('id_jadwal') ?? 0) + 1;
        Jadwal::create([
            'id_jadwal'   => $nextIdJadwal,
            'id_kelas'    => $kelas->id_kelas,
            'id_tentor'   => $tentor->id_user,
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => $request->status,
        ]);

        return redirect()->route('operator.jadwal')->with('success', 'Jadwal berhasil ditambahkan!');
    }
    
}


