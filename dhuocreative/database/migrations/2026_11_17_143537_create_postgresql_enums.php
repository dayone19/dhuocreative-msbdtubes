<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
// buat semua enum untuk di tabel
    public function up(): void
    {
        DB::unprepared("
            CREATE TYPE public.jenis_kelas AS ENUM (
                'Online Class',
                'Private Class',
                'Home Class'
            );

            CREATE TYPE public.jenis_tugas AS ENUM (
                'tugas',
                'projek'
            );

            CREATE TYPE public.role_user AS ENUM (
                'admin',
                'operator',
                'tentor',
                'siswa'
            );

            CREATE TYPE public.siswa_kelas_status AS ENUM (
                'aktif',
                'selesai',
                'keluar'
            );

            CREATE TYPE public.siswa_kursus_status AS ENUM (
                'aktif',
                'selesai',
                'keluar'
            );

            CREATE TYPE public.status_absensi AS ENUM (
                'hadir',
                'izin',
                'sakit',
                'alpha'
            );

            CREATE TYPE public.status_jadwal AS ENUM (
                'terjadwal',
                'selesai',
                'dibatalkan'
            );

            CREATE TYPE public.status_kelas AS ENUM (
                'aktif',
                'selesai',
                'nonaktif'
            );

            CREATE TYPE public.status_kursus AS ENUM (
                'aktif',
                'nonaktif'
            );

            CREATE TYPE public.status_pengajuan AS ENUM (
                'menunggu',
                'disetujui',
                'ditolak'
            );

            CREATE TYPE public.status_pengumpulan_tugas AS ENUM (
                'terkumpul',
                'terlambat',
                'belum_mengumpulkan'
            );

            CREATE TYPE public.status_pengumuman AS ENUM (
                'aktif',
                'nonaktif'
            );

            CREATE TYPE public.status_user AS ENUM (
                'aktif',
                'nonaktif'
            );
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TYPE IF EXISTS public.status_user;
            DROP TYPE IF EXISTS public.status_pengumuman;
            DROP TYPE IF EXISTS public.status_pengumpulan_tugas;
            DROP TYPE IF EXISTS public.status_pengajuan;
            DROP TYPE IF EXISTS public.status_kursus;
            DROP TYPE IF EXISTS public.status_kelas;
            DROP TYPE IF EXISTS public.status_jadwal;
            DROP TYPE IF EXISTS public.status_absensi;
            DROP TYPE IF EXISTS public.siswa_kursus_status;
            DROP TYPE IF EXISTS public.siswa_kelas_status;
            DROP TYPE IF EXISTS public.role_user;
            DROP TYPE IF EXISTS public.jenis_tugas;
            DROP TYPE IF EXISTS public.jenis_kelas;
        ");
    }
};
