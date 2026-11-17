
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'users',
            'kursus',
            'kelas',
            'jadwal',
            'siswa_kursus',
            'absensi_siswa',
            'absensi_tentor',
            'tugas',
            'pengumpulan_tugas',
            'nilai',
            'pengumuman',
            'sertifikat',
        ];

        foreach ($tables as $table) {
            DB::unprepared("
                CREATE TRIGGER trg_audit_{$table}
                AFTER INSERT OR UPDATE OR DELETE
                ON public.{$table}
                FOR EACH ROW
                EXECUTE FUNCTION public.audit_log_aktivitas();
            ");
        }
    }

    public function down(): void
    {
        $tables = [
            'users',
            'kursus',
            'kelas',
            'jadwal',
            'siswa_kursus',
            'absensi_siswa',
            'absensi_tentor',
            'tugas',
            'pengumpulan_tugas',
            'nilai',
            'pengumuman',
            'sertifikat',
        ];

        foreach ($tables as $table) {
            DB::unprepared("
                DROP TRIGGER IF EXISTS trg_audit_{$table}
                ON public.{$table};
            ");
        }
    }
};
