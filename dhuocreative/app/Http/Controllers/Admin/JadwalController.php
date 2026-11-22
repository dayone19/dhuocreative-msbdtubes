<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Kursus;
use App\Models\User;
use App\Models\PengajuanJadwal;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = Jadwal::with(['kelas.kursus', 'kelas.siswa', 'tentor'])
                         ->orderBy('tanggal', 'desc')
                         ->orderBy('jam_mulai', 'asc')
                         ->get();

        $tanggalAcuan = $request->filled('minggu')
            ? \Carbon\Carbon::parse($request->minggu)
            : \Carbon\Carbon::now();

        $startOfWeek = $tanggalAcuan->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        $endOfWeek   = $tanggalAcuan->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);

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
            $tglHari = $startOfWeek->copy()->addDays($isoDay - 1)->format('Y-m-d');
            $tglLabel = $startOfWeek->copy()->addDays($isoDay - 1)->format('d M');

           $jadwalHari = $jadwals->filter(function ($j) use ($tglHari) {
                return \Carbon\Carbon::parse($j->tanggal)->format('Y-m-d') === $tglHari
                    && $j->status === 'terjadwal';
            })->sortBy('jam_mulai')->values();

            $jadwalMingguan[$namaHari] = [
                'tanggal' => $tglLabel,
                'jadwals' => $jadwalHari,
            ];
        }

        $prevWeek = $startOfWeek->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $startOfWeek->copy()->addWeek()->format('Y-m-d');
        $labelMinggu = $startOfWeek->format('d M Y') . ' – ' . $endOfWeek->format('d M Y');

        $kursusList = Kursus::orderBy('nama_kursus', 'asc')->get();
        $kelasList  = Kelas::with(['kursus', 'siswa'])->get();
        $tentorList = User::where('role', 'tentor')->orderBy('nama', 'asc')->get();
        $siswaList  = User::where('role', 'siswa')->orderBy('nama', 'asc')->get();

        $pengajuanList = PengajuanJadwal::with(['jadwalLama.kelas.kursus', 'tentor', 'kelas.siswa'])->get();

        $siswaMapping = [];
        foreach ($siswaList as $s) {
            $classes = Kelas::where('id_siswa', $s->id_user)->with('kursus')->get();
            $courses = [];
            foreach ($classes as $c) {
                $tentorName = Jadwal::where('id_kelas', $c->id_kelas)->with('tentor')->first()?->tentor?->nama;
                
                if (!$tentorName && $c->kursus) {
                    $tentorUser = DB::table('tentor_kursus')
                        ->join('users', 'tentor_kursus.id_tentor', '=', 'users.id_user')
                        ->where('tentor_kursus.id_kursus', $c->id_kursus)
                        ->first();
                    $tentorName = $tentorUser?->nama;
                }

                $courses[] = [
                    'id_kelas' => $c->id_kelas,
                    'kursus'   => $c->kursus->nama_kursus ?? '',
                    'kelas'    => $c->kelas,
                    'tentor'   => $tentorName ?? '',
                ];
            }
            $siswaMapping[$s->nama] = $courses;
        }

        $jadwalExisting = $jadwals->where('status', '!=', 'dibatalkan')->map(function ($j) {
            return [
                'id'          => $j->id_jadwal,
                'tentor'      => $j->tentor->nama ?? '',
                'siswa'       => $j->kelas->siswa->nama ?? '',
                'tanggal'     => $j->tanggal,
                'jam_mulai'   => substr($j->jam_mulai, 0, 5),
                'jam_selesai' => substr($j->jam_selesai, 0, 5),
            ];
        })->values();

        return view('admin.jadwalAbsensi', compact(
            'jadwals',
            'jadwalMingguan',
            'labelMinggu',
            'prevWeek',
            'nextWeek',
            'kursusList',
            'kelasList',
            'tentorList',
            'siswaList',
            'pengajuanList',
            'siswaMapping',
            'jadwalExisting'
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

        if ($request->jam_mulai > '20:00') {
            return redirect()->back()->with('error', 'Jam mulai pembelajaran paling lambat pukul 20:00 (durasi kursus 2 jam, selesai maks. pukul 22:00).')->withInput();
        }

        if ($request->jam_selesai > '22:00') {
            return redirect()->back()->with('error', 'Jam selesai pembelajaran tidak boleh di atas pukul 22:00.')->withInput();
        }

        if ($request->jam_selesai <= $request->jam_mulai) {
            $parts = explode(':', $request->jam_mulai);
            $endH = min(22, (int)$parts[0] + 2);
            $request->merge(['jam_selesai' => str_pad($endH, 2, '0', STR_PAD_LEFT) . ':' . ($parts[1] ?? '00')]);
        }

        try {
            $tentor = User::where('nama', $request->tentor)->where('role', 'tentor')->first();
            $siswa  = User::where('nama', $request->siswa)->where('role', 'siswa')->first();
            $kursus = Kursus::where('nama_kursus', $request->kursus)->first();

            if (!$tentor || !$siswa || !$kursus) {
                return redirect()->back()->with('error', 'Data tentor, siswa, atau kursus tidak valid.')->withInput();
            }

            $namaKelas = $request->kelas;
            if (strtolower($namaKelas) === 'online' || strtolower($namaKelas) === 'online class') {
                $namaKelas = 'Online Class';
            } elseif (strtolower($namaKelas) === 'offline' || strtolower($namaKelas) === 'private class') {
                $namaKelas = 'Private Class';
            } elseif (strtolower($namaKelas) === 'offline rumah' || strtolower($namaKelas) === 'home class') {
                $namaKelas = 'Home Class';
            }

            $bentrokTentor = Jadwal::where('id_tentor', $tentor->id_user)
                ->where('tanggal', $request->tanggal)
                ->where('status', '!=', 'dibatalkan')
                ->when($request->filled('id_jadwal'), function ($q) use ($request) {
                    $q->where('id_jadwal', '!=', $request->id_jadwal);
                })
                ->where('jam_mulai', '<', $request->jam_selesai)
                ->where('jam_selesai', '>', $request->jam_mulai)
                ->first();

            if ($bentrokTentor) {
                $mulai = substr($bentrokTentor->jam_mulai, 0, 5);
                $selesai = substr($bentrokTentor->jam_selesai, 0, 5);
                return redirect()->back()
                    ->with('error', "Jadwal bentrok! Tentor {$tentor->nama} sudah memiliki jadwal lain pada tanggal tersebut pukul {$mulai}–{$selesai}.")
                    ->withInput();
            }

            $bentrokSiswa = Jadwal::whereHas('kelas', function ($q) use ($siswa) {
                    $q->where('id_siswa', $siswa->id_user);
                })
                ->where('tanggal', $request->tanggal)
                ->where('status', '!=', 'dibatalkan')
                ->when($request->filled('id_jadwal'), function ($q) use ($request) {
                    $q->where('id_jadwal', '!=', $request->id_jadwal);
                })
                ->where('jam_mulai', '<', $request->jam_selesai)
                ->where('jam_selesai', '>', $request->jam_mulai)
                ->first();

            if ($bentrokSiswa) {
                $mulai = substr($bentrokSiswa->jam_mulai, 0, 5);
                $selesai = substr($bentrokSiswa->jam_selesai, 0, 5);
                return redirect()->back()
                    ->with('error', "Jadwal bentrok! Siswa {$siswa->nama} sudah memiliki jadwal kelas lain pada tanggal tersebut pukul {$mulai}–{$selesai}.")
                    ->withInput();
            }

            $tentorMengajar = DB::table('tentor_kursus')
                ->where('id_tentor', $tentor->id_user)
                ->where('id_kursus', $kursus->id_kursus)
                ->exists();

            if (!$tentorMengajar) {
                $nextTkId = (DB::table('tentor_kursus')->max('id_tentor_kursus') ?? 0) + 1;
                DB::table('tentor_kursus')->insert([
                    'id_tentor_kursus' => $nextTkId,
                    'id_tentor'        => $tentor->id_user,
                    'id_kursus'        => $kursus->id_kursus,
                    'tanggal_daftar'   => now(),
                ]);
            }

            $kelas = Kelas::where('id_kursus', $kursus->id_kursus)
                          ->where('kelas', $namaKelas)
                          ->where('id_siswa', $siswa->id_user)
                          ->first();

            if (!$kelas) {
                $nextIdKelas = (Kelas::max('id_kelas') ?? 0) + 1;
                $kelas = Kelas::create([
                    'id_kelas'   => $nextIdKelas,
                    'id_kursus'  => $kursus->id_kursus,
                    'status'     => 'aktif',
                    'kelas'      => $namaKelas,
                    'id_siswa'   => $siswa->id_user,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

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

                    return redirect()->route('admin.jadwalAbsensi')->with('success', 'Jadwal berhasil diperbarui!');
                }
            }

            $lastPertemuan = Jadwal::where('id_kelas', $kelas->id_kelas)->max('pertemuan_ke') ?? 0;
            $pertemuanKe = $lastPertemuan + 1;

            if ($pertemuanKe > 15) {
                return redirect()->back()->with('error', 'Kelas ini sudah mencapai batas maksimal 15 pertemuan.')->withInput();
            }

            Jadwal::create([
                'id_kelas'     => $kelas->id_kelas,
                'id_tentor'    => $tentor->id_user,
                'tanggal'      => $request->tanggal,
                'jam_mulai'    => $request->jam_mulai,
                'jam_selesai'  => $request->jam_selesai,
                'status'       => $request->status,
                'pertemuan_ke' => $pertemuanKe,
            ]);

            return redirect()->route('admin.jadwalAbsensi')->with('success', "Jadwal Pertemuan ke-{$pertemuanKe} berhasil ditambahkan!");
        } catch (\Throwable $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'Tentor tidak sesuai')) {
                $pesan = 'Gagal: Tentor yang dipilih tidak mengajar kursus ini!';
            } elseif (str_contains($msg, 'Jadwal tentor bentrok')) {
                $pesan = 'Gagal: Jadwal tentor bentrok dengan kelas lain pada jam dan tanggal yang sama!';
            } elseif (str_contains($msg, 'chk_pertemuan_ke')) {
                $pesan = 'Gagal: Nomor pertemuan harus antara 1 sampai 15!';
            } else {
                $pesan = 'Gagal menyimpan jadwal: ' . $msg;
            }
            return redirect()->back()->with('error', $pesan)->withInput();
        }
    }

    public function approveReschedule(Request $request)
    {
        $request->validate([
            'id_pengajuan' => 'required',
            'aksi'         => 'required|in:setuju,tolak',
        ]);

        $pengajuan = PengajuanJadwal::find($request->id_pengajuan);
        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Data pengajuan tidak ditemukan.');
        }

        if ($request->aksi === 'setuju') {
            $pengajuan->update(['status' => 'disetujui']);

            if ($pengajuan->jadwalLama) {
                $pengajuan->jadwalLama->update([
                    'tanggal'     => $pengajuan->tanggal_baru,
                    'jam_mulai'   => $pengajuan->jam_mulai,
                    'jam_selesai' => $pengajuan->jam_selesai,
                    'status'      => 'terjadwal',
                ]);
            }
            $pesan = 'Pengajuan pergantian jadwal berhasil disetujui!';
        } else {
            $pengajuan->update(['status' => 'ditolak']);
            $pesan = 'Pengajuan pergantian jadwal ditolak.';
        }

        return redirect()->back()->with('success', $pesan);
    }
}
