
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'

        -- 1. TRIGGER VALIDASI ABSENSI SISWA

        CREATE TRIGGER trg_validasi_absensi_siswa
        BEFORE INSERT OR UPDATE
        ON public.absensi_siswa
        FOR EACH ROW
        EXECUTE FUNCTION public.trg_validasi_absensi_siswa();


        -- 2. TRIGGER VALIDASI ABSENSI TENTOR

        CREATE TRIGGER trg_validasi_absensi_tentor
        BEFORE INSERT OR UPDATE
        ON public.absensi_tentor
        FOR EACH ROW
        EXECUTE FUNCTION public.trg_validasi_absensi_tentor();


        -- 3. TRIGGER VALIDASI JADWAL

        CREATE TRIGGER trg_validasi_jadwal
        BEFORE INSERT OR UPDATE
        ON public.jadwal
        FOR EACH ROW
        EXECUTE FUNCTION public.trg_validasi_jadwal();


        -- 4. TRIGGER VALIDASI PEMBUAT PENGUMUMAN

        CREATE TRIGGER trg_validasi_pembuat_pengumuman
        BEFORE INSERT
        ON public.pengumuman
        FOR EACH ROW
        EXECUTE FUNCTION public.trg_validasi_pembuat_pengumuman();


        -- 5. TRIGGER VALIDASI PENGUMPULAN TUGAS

        CREATE TRIGGER trg_validasi_pengumpulan_tugas
        BEFORE INSERT OR UPDATE
        ON public.pengumpulan_tugas
        FOR EACH ROW
        EXECUTE FUNCTION public.trg_validasi_pengumpulan_tugas();


        -- 6. TRIGGER VALIDASI PENILAIAN

        CREATE TRIGGER trg_validasi_penilaian
        BEFORE INSERT OR UPDATE
        ON public.nilai
        FOR EACH ROW
        EXECUTE FUNCTION public.trg_validasi_penilaian();


        -- 7. TRIGGER VALIDASI STATUS KURSUS

        CREATE TRIGGER trg_validasi_status_kursus
        BEFORE INSERT OR UPDATE
        ON public.siswa_kursus
        FOR EACH ROW
        EXECUTE FUNCTION public.trg_validasi_status_kursus();


        -- 8. TRIGGER VALIDASI TENTOR PENILAI

        CREATE TRIGGER trg_validasi_tentor_penilai
        BEFORE INSERT OR UPDATE
        ON public.nilai
        FOR EACH ROW
        EXECUTE FUNCTION public.trg_validasi_tentor_penilai();

        SQL);
    }

    public function down(): void
    {
        DB::unprepared(<<<'SQL'

        DROP TRIGGER IF EXISTS trg_validasi_tentor_penilai
        ON public.nilai;

        DROP TRIGGER IF EXISTS trg_validasi_status_kursus
        ON public.siswa_kursus;

        DROP TRIGGER IF EXISTS trg_validasi_penilaian
        ON public.nilai;

        DROP TRIGGER IF EXISTS trg_validasi_pengumpulan_tugas
        ON public.pengumpulan_tugas;

        DROP TRIGGER IF EXISTS trg_validasi_pembuat_pengumuman
        ON public.pengumuman;

        DROP TRIGGER IF EXISTS trg_validasi_jadwal
        ON public.jadwal;

        DROP TRIGGER IF EXISTS trg_validasi_absensi_tentor
        ON public.absensi_tentor;

        DROP TRIGGER IF EXISTS trg_validasi_absensi_siswa
        ON public.absensi_siswa;

        SQL);
    }
};
