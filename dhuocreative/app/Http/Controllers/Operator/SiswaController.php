<?php
// Controller Siswa Operator - Dhuo Creative

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kursus;
use App\Models\Kelas;
use App\Models\SiswaKursus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function index()
    {
        $siswaRaw = User::where('role', 'siswa')
            ->orderBy('id_user', 'asc')
            ->get();

        $siswaList = $siswaRaw->map(function ($s) {
            $courses = DB::table('siswa_kursus')
                ->join('kursus', 'siswa_kursus.id_kursus', '=', 'kursus.id_kursus')
                ->leftJoin('kelas', function ($join) use ($s) {
                    $join->on('kelas.id_kursus', '=', 'kursus.id_kursus')
                        ->where('kelas.id_siswa', '=', $s->id_user);
                })
                ->where('siswa_kursus.id_siswa', $s->id_user)
                ->select(
                    'kursus.id_kursus',
                    'kursus.nama_kursus',
                    'kelas.kelas as jenis_kelas',
                    'siswa_kursus.status',
                    'siswa_kursus.tanggal_daftar',
                    DB::raw("(
                        SELECT STRING_AGG(DISTINCT u.nama, ', ')
                        FROM jadwal j
                        JOIN users u ON j.id_tentor = u.id_user
                        WHERE j.id_kelas = kelas.id_kelas
                        AND j.status <> 'dibatalkan'
                    ) as nama_tentor")
                )
                ->get();

            $firstCourse = $courses->first();
            $tglDaftar = $firstCourse && $firstCourse->tanggal_daftar
                ? Carbon::parse($firstCourse->tanggal_daftar)->translatedFormat('d M Y')
                : ($s->created_at ? Carbon::parse($s->created_at)->translatedFormat('d M Y') : '12 Jan 2026');

            $s->id_str = 'S-' . str_pad($s->id_user, 3, '0', STR_PAD_LEFT);
            $s->tanggal_daftar_teks = $tglDaftar;
            $s->daftar_kursus = $courses;

            return $s;
        });

        $kursusList = Kursus::orderBy('nama_kursus', 'asc')->get();

        $akunSiswaList = User::where('role', 'siswa')
        ->where('status', 'aktif')
        ->orderBy('nama', 'asc')
        ->get(['id_user', 'nama', 'no_telp']);


        return view('operator.siswa', compact(
            'siswaList', 
            'kursusList',
           'akunSiswaList'
        ));
    }

    private function buatTanggalPertemuan($tanggalDaftar, $hari)
    {
        $hariMapping = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
        ];

        $hariDipilih = collect($hari)
            ->map(fn($h) => $hariMapping[$h])
            ->unique()
            ->values()
            ->all();

        $tanggal = Carbon::parse($tanggalDaftar)->startOfDay();
        $hasil = [];

        while (count($hasil) < 15) {
            if (in_array($tanggal->dayOfWeekIso, $hariDipilih)) {
                $hasil[] = $tanggal->toDateString();
            }

            $tanggal->addDay();
        }

        return $hasil;
    }

    private function cekKetersediaanTentor(
        $idKursus,
        $tanggalPertemuan,
        $jamMulai,
        $jamSelesai
    ) {
        $calonTentor = DB::table('tentor_kursus')
            ->join('users', 'tentor_kursus.id_tentor', '=', 'users.id_user')
            ->where('tentor_kursus.id_kursus', $idKursus)
            ->where('users.role', 'tentor')
            ->select('users.id_user', 'users.nama')
            ->distinct()
            ->get();

        $hasil = [];

        foreach ($calonTentor as $tentor) {
            $bentrok = false;

            foreach ($tanggalPertemuan as $tanggal) {
                $adaBentrok = DB::table('jadwal')
                    ->where('id_tentor', $tentor->id_user)
                    ->where('tanggal', $tanggal)
                    ->where('status', '<>', 'dibatalkan')
                    ->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai)
                    ->exists();

                if ($adaBentrok) {
                    $bentrok = true;
                    break;
                }
            }

            $jumlahJadwal = DB::table('jadwal')
                ->where('id_tentor', $tentor->id_user)
                ->where('status', 'terjadwal')
                ->count();

            $hasil[] = [
                'id_tentor' => $tentor->id_user,
                'nama' => $tentor->nama,
                'bentrok' => $bentrok,
                'tersedia' => !$bentrok,
                'jumlah_jadwal' => $jumlahJadwal,
            ];
        }

        usort($hasil, function ($a, $b) {
            return ($a['bentrok'] <=> $b['bentrok'])
                ?: ($a['jumlah_jadwal'] <=> $b['jumlah_jadwal'])
                ?: ($a['id_tentor'] <=> $b['id_tentor']);
        });

        return $hasil;
    }

    public function cekTentor(Request $request)
    {
        $validated = $request->validate([
            'id_kursus' => 'required|integer|exists:kursus,id_kursus',
            'hari' => 'required|array|min:1|max:3',
            'hari.*' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'tanggal_daftar' => 'required|date',
        ]);

        $jamMulai = Carbon::createFromFormat(
            'H:i',
            $validated['jam_mulai']
        );

        $jamSelesai = $jamMulai->copy()->addHours(2);

        if (
            $jamMulai->format('H:i') < '07:00' ||
            $jamMulai->format('H:i') > '20:00' ||
            $jamSelesai->format('H:i') > '22:00'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Jam pembelajaran harus berada antara 07:00 sampai 22:00.'
            ], 422);
        }

        $tanggalPertemuan = $this->buatTanggalPertemuan(
            $validated['tanggal_daftar'],
            $validated['hari']
        );

        $tentor = $this->cekKetersediaanTentor(
            $validated['id_kursus'],
            $tanggalPertemuan,
            $jamMulai->format('H:i:s'),
            $jamSelesai->format('H:i:s')
        );

        return response()->json([
            'success' => true,
            'tentor' => $tentor,
            'jumlah_pertemuan' => count($tanggalPertemuan),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|integer|exists:users,id_user',
            'id_kursus' => 'required|integer|exists:kursus,id_kursus',
            'id_tentor' => 'required|integer|exists:users,id_user',
            'kelas' => 'required|in:Online Class,Private Class,Home Class',
            'hari' => 'required|array|min:1|max:3',
            'hari.*' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'tanggal_daftar' => 'required|date',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        try {
            DB::beginTransaction();

            $user = User::where('id_user', $validated['id_siswa'])
                ->where('role', 'siswa')
                ->where('status', 'aktif')
                ->first();

            if (!$user) {
                throw new \Exception('Akun siswa tidak ditemukan atau tidak aktif.');
            }

            $sudahTerdaftar = SiswaKursus::where('id_siswa', $user->id_user)
                ->where('id_kursus', $validated['id_kursus'])
                ->exists();

            if ($sudahTerdaftar) {
                throw new \Exception('Siswa sudah terdaftar pada kursus tersebut.');
            }

            $jamMulai = Carbon::createFromFormat(
                'H:i',
                $validated['jam_mulai']
            );

            $jamSelesai = $jamMulai->copy()->addHours(2);

            if (
                $jamMulai->format('H:i') < '07:00' ||
                $jamMulai->format('H:i') > '20:00' ||
                $jamSelesai->format('H:i') > '22:00'
            ) {
                throw new \Exception('Jam pembelajaran harus antara 07:00 sampai 22:00.');
            }

            $tglDaftar = Carbon::parse($validated['tanggal_daftar'])
                ->startOfDay();

            $tanggalPertemuan = $this->buatTanggalPertemuan(
                $validated['tanggal_daftar'],
                $validated['hari']
            );

            // Periksa ulang ketersediaan seluruh tentor.
            $daftarTentor = $this->cekKetersediaanTentor(
                $validated['id_kursus'],
                $tanggalPertemuan,
                $jamMulai->format('H:i:s'),
                $jamSelesai->format('H:i:s')
            );

            $tentorDipilih = collect($daftarTentor)
                ->firstWhere('id_tentor', (int) $validated['id_tentor']);

            if (!$tentorDipilih) {
                throw new \Exception(
                    'Tentor tidak terdaftar pada kursus yang dipilih.'
                );
            }

            if (!$tentorDipilih['tersedia']) {
                throw new \Exception(
                    'Tentor yang dipilih memiliki jadwal bentrok. Silakan pilih tentor lain.'
                );
            }

            // Simpan pendaftaran kursus siswa yang sudah memiliki akun.
            $nextIdSiswaKursus = (SiswaKursus::max('id_siswa_kursus') ?? 0) + 1;

            try {
                SiswaKursus::create([
                    'id_siswa_kursus' => $nextIdSiswaKursus,
                    'id_siswa' => $user->id_user,
                    'id_kursus' => $validated['id_kursus'],
                    'tanggal_daftar' => $tglDaftar->toDateString(),
                    'status' => $validated['status'],
                ]);
            } catch (\Throwable $e) {
                SiswaKursus::create([
                    'id_siswa_kursus' => $nextIdSiswaKursus,
                    'id_siswa' => $user->id_user,
                    'id_kursus' => $validated['id_kursus'],
                    'tanggal_daftar' => $tglDaftar->toDateString(),
                    'status' => $validated['status'] === 'nonaktif'
                        ? 'keluar'
                        : 'aktif',
                ]);
            }

            // Buat kelas siswa.
            $nextIdKelas = (Kelas::max('id_kelas') ?? 0) + 1;

            $kelas = Kelas::create([
                'id_kelas' => $nextIdKelas,
                'id_kursus' => $validated['id_kursus'],
                'id_siswa' => $user->id_user,
                'kelas' => $validated['kelas'],
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buat 15 jadwal dengan tentor yang sama.
            foreach ($tanggalPertemuan as $index => $tanggal) {
                DB::table('jadwal')->insert([
                    'id_kelas' => $kelas->id_kelas,
                    'id_tentor' => $tentorDipilih['id_tentor'],
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jamMulai->format('H:i:s'),
                    'jam_selesai' => $jamSelesai->format('H:i:s'),
                    'status' => 'terjadwal',
                    'pertemuan_ke' => $index + 1,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Pendaftaran siswa {$user->nama} berhasil disimpan!",
                'siswa' => $user,
                'tentor' => $tentorDipilih['nama'],
                'jumlah_pertemuan' => 15,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateKursus(Request $request, $id, $id_kursus)
    {
        $numericId = (int) preg_replace('/[^0-9]/', '', (string)$id);
        $user = User::where('id_user', $numericId)->where('role', 'siswa')->first();
        if (!$user) {
            $user = User::where('id_user', $id)->first();
        }
        abort_if(!$user, 404, 'Data siswa tidak ditemukan.');

        $numericOldKursusId = (int) preg_replace('/[^0-9]/', '', (string)$id_kursus);

        $newKursusId = $request->filled('id_kursus') && is_numeric($request->id_kursus)
            ? (int) $request->id_kursus
            : $numericOldKursusId;

        $newKursus = Kursus::find($newKursusId);
        abort_if(!$newKursus, 404, 'Kursus tidak ditemukan.');

        $status = in_array(strtolower($request->status ?? ''), ['aktif', 'nonaktif']) ? strtolower($request->status) : 'aktif';
        $jenisKelas = $request->kelas ?: 'Online Class';
        $tglDaftar = $request->tanggal_daftar ?: date('Y-m-d');

        try {
            DB::beginTransaction();

            $sk = SiswaKursus::where('id_siswa', $user->id_user)
                ->where('id_kursus', $numericOldKursusId)
                ->first();

            if ($sk) {
                try {
                    $sk->update([
                        'id_kursus'      => $newKursusId,
                        'status'         => $status,
                        'tanggal_daftar' => $tglDaftar,
                    ]);
                } catch (\Throwable $e) {
                    $sk->update([
                        'id_kursus'      => $newKursusId,
                        'status'         => ($status === 'nonaktif' ? 'keluar' : 'aktif'),
                        'tanggal_daftar' => $tglDaftar,
                    ]);
                }
            } else {
                $nextIdSiswaKursus = (SiswaKursus::max('id_siswa_kursus') ?? 0) + 1;
                try {
                    $sk = SiswaKursus::create([
                        'id_siswa_kursus' => $nextIdSiswaKursus,
                        'id_siswa'        => $user->id_user,
                        'id_kursus'       => $newKursusId,
                        'tanggal_daftar'  => $tglDaftar,
                        'status'          => $status,
                    ]);
                } catch (\Throwable $e) {
                    $sk = SiswaKursus::create([
                        'id_siswa_kursus' => $nextIdSiswaKursus,
                        'id_siswa'        => $user->id_user,
                        'id_kursus'       => $newKursusId,
                        'tanggal_daftar'  => $tglDaftar,
                        'status'          => ($status === 'nonaktif' ? 'keluar' : 'aktif'),
                    ]);
                }
            }

            $kelas = Kelas::where('id_siswa', $user->id_user)
                ->where('id_kursus', $numericOldKursusId)
                ->first();

            if ($kelas) {
                $kelas->update([
                    'id_kursus'  => $newKursusId,
                    'kelas'      => $jenisKelas,
                    'status'     => $status,
                    'updated_at' => now(),
                ]);
            } else {
                $nextIdKelas = (Kelas::max('id_kelas') ?? 0) + 1;
                $kelas = Kelas::create([
                    'id_kelas'   => $nextIdKelas,
                    'id_kursus'  => $newKursusId,
                    'id_siswa'   => $user->id_user,
                    'kelas'      => $jenisKelas,
                    'status'     => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Kursus siswa {$user->nama} berhasil diperbarui!",
                'data'    => [
                    'id_kursus'      => $newKursusId,
                    'nama_kursus'    => $newKursus->nama_kursus,
                    'jenis_kelas'    => $jenisKelas,
                    'status'         => $status,
                    'tanggal_daftar' => $tglDaftar,
                ]
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kursus siswa: ' . $e->getMessage()
            ], 500);
        }
    }
}
