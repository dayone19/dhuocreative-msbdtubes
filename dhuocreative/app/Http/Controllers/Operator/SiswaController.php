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

        return view('operator.siswa', compact('siswaList', 'kursusList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
        ], [
            'nama.required'    => 'Nama siswa wajib diisi.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
        ]);

        try {
            DB::beginTransaction();

            $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->nama));
            $email = $request->email ?: ($cleanName . rand(100, 999) . '@dhuocreative.com');
            $tglDaftar = $request->tanggal_daftar ?: date('Y-m-d');
            $status = in_array(strtolower($request->status ?? ''), ['aktif', 'nonaktif']) ? strtolower($request->status) : 'aktif';

            $nextIdUser = (User::max('id_user') ?? 0) + 1;
            $user = User::create([
                'id_user'     => $nextIdUser,
                'nama'        => $request->nama,
                'email'       => $email,
                'no_telp'     => $request->no_telp,
                'pasword'     => Hash::make('Siswa123'),
                'role'        => 'siswa',
                'tahun_masuk' => (int) date('Y', strtotime($tglDaftar)),
                'status'      => $status,
                'created_at'  => now(),
                'update_at'   => now(),
            ]);

            $idKursus = null;
            if ($request->filled('id_kursus')) {
                if (is_numeric($request->id_kursus)) {
                    $idKursus = (int) $request->id_kursus;
                } else {
                    $k = Kursus::where('nama_kursus', 'ilike', '%' . $request->id_kursus . '%')->first();
                    $idKursus = $k ? $k->id_kursus : null;
                }
            }

            if ($idKursus) {
                $nextIdSiswaKursus = (SiswaKursus::max('id_siswa_kursus') ?? 0) + 1;
                try {
                    SiswaKursus::create([
                        'id_siswa_kursus' => $nextIdSiswaKursus,
                        'id_siswa'        => $user->id_user,
                        'id_kursus'       => $idKursus,
                        'tanggal_daftar'  => $tglDaftar,
                        'status'          => $status,
                    ]);
                } catch (\Throwable $e) {
                    SiswaKursus::create([
                        'id_siswa_kursus' => $nextIdSiswaKursus,
                        'id_siswa'        => $user->id_user,
                        'id_kursus'       => $idKursus,
                        'tanggal_daftar'  => $tglDaftar,
                        'status'          => ($status === 'nonaktif' ? 'keluar' : 'aktif'),
                    ]);
                }

                $jenisKelas = $request->kelas ?: 'Online Class';
                $nextIdKelas = (Kelas::max('id_kelas') ?? 0) + 1;
                Kelas::create([
                    'id_kelas'   => $nextIdKelas,
                    'id_kursus'  => $idKursus,
                    'id_siswa'   => $user->id_user,
                    'kelas'      => $jenisKelas,
                    'status'     => 'aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Pendaftaran siswa {$request->nama} berhasil disimpan!",
                    'siswa'   => $user
                ]);
            }

            return redirect()->route('operator.siswa')->with('success', "Pendaftaran siswa {$request->nama} berhasil disimpan!");
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal mendaftarkan siswa: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $numericId = (int) preg_replace('/[^0-9]/', '', (string)$id);
        $user = User::where('id_user', $numericId)->where('role', 'siswa')->first();

        if (!$user) {
            $user = User::where('id_user', $id)->first();
        }

        abort_if(!$user, 404, 'Data siswa tidak ditemukan.');

        $validated = $request->validate([
            'nama'    => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
        ], [
            'nama.required'    => 'Nama siswa wajib diisi.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
        ]);

        try {
            $updateData = [
                'nama'      => $validated['nama'],
                'no_telp'   => $validated['no_telp'],
                'update_at' => now(),
            ];

            if ($request->filled('status')) {
                $updateData['status'] = strtolower($request->status);
            }

            $user->update($updateData);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data siswa {$user->nama} berhasil diperbarui!",
                    'siswa'   => $user
                ]);
            }

            return redirect()->route('operator.siswa')->with('success', "Data siswa {$user->nama} berhasil diperbarui!");
        } catch (\Throwable $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal memperbarui data siswa: ' . $e->getMessage())->withInput();
        }
    }

    public function tambahKursus(Request $request, $id)
    {
        $numericId = (int) preg_replace('/[^0-9]/', '', (string)$id);
        $user = User::where('id_user', $numericId)->where('role', 'siswa')->first();

        if (!$user) {
            $user = User::where('id_user', $id)->first();
        }

        abort_if(!$user, 404, 'Data siswa tidak ditemukan.');

        $request->validate([
            'id_kursus' => 'required',
        ], [
            'id_kursus.required' => 'Kursus wajib dipilih.',
        ]);

        $idKursus = null;
        if (is_numeric($request->id_kursus)) {
            $idKursus = (int) $request->id_kursus;
        } else {
            $k = Kursus::where('nama_kursus', 'ilike', '%' . $request->id_kursus . '%')->first();
            $idKursus = $k ? $k->id_kursus : null;
        }

        abort_if(!$idKursus, 400, 'Kursus tidak valid.');

        $kursus = Kursus::find($idKursus);
        abort_if(!$kursus, 404, 'Kursus tidak ditemukan.');

        $cek = SiswaKursus::where('id_siswa', $user->id_user)->where('id_kursus', $idKursus)->first();
        if ($cek) {
            return response()->json([
                'success' => false,
                'message' => "Siswa sudah terdaftar pada kursus {$kursus->nama_kursus}."
            ], 422);
        }

        $status = in_array(strtolower($request->status ?? ''), ['aktif', 'nonaktif']) ? strtolower($request->status) : 'aktif';
        $tglDaftar = $request->tanggal_daftar ?: date('Y-m-d');
        $jenisKelas = $request->kelas ?: 'Online Class';

        try {
            DB::beginTransaction();

            $nextIdSiswaKursus = (SiswaKursus::max('id_siswa_kursus') ?? 0) + 1;
            try {
                SiswaKursus::create([
                    'id_siswa_kursus' => $nextIdSiswaKursus,
                    'id_siswa'        => $user->id_user,
                    'id_kursus'       => $idKursus,
                    'tanggal_daftar'  => $tglDaftar,
                    'status'          => $status,
                ]);
            } catch (\Throwable $e) {
                SiswaKursus::create([
                    'id_siswa_kursus' => $nextIdSiswaKursus,
                    'id_siswa'        => $user->id_user,
                    'id_kursus'       => $idKursus,
                    'tanggal_daftar'  => $tglDaftar,
                    'status'          => ($status === 'nonaktif' ? 'keluar' : 'aktif'),
                ]);
            }

            $nextIdKelas = (Kelas::max('id_kelas') ?? 0) + 1;
            Kelas::create([
                'id_kelas'   => $nextIdKelas,
                'id_kursus'  => $idKursus,
                'id_siswa'   => $user->id_user,
                'kelas'      => $jenisKelas,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Kursus {$kursus->nama_kursus} berhasil ditambahkan ke siswa {$user->nama}!",
                'data'    => [
                    'id_kursus'      => $idKursus,
                    'nama_kursus'    => $kursus->nama_kursus,
                    'jenis_kelas'    => $jenisKelas,
                    'status'         => $status,
                    'tanggal_daftar' => $tglDaftar,
                ]
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kursus ke siswa: ' . $e->getMessage()
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
