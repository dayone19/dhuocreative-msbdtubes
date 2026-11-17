<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE jadwal
            ADD CONSTRAINT chk_hari_jadwal
            CHECK (EXTRACT(ISODOW FROM tanggal) BETWEEN 1 AND 6)
        ");

        DB::statement("
            ALTER TABLE jadwal
            ADD CONSTRAINT chk_jam_jadwal
            CHECK (jam_selesai > jam_mulai)
        ");

        DB::statement("
            ALTER TABLE jadwal
            ADD CONSTRAINT chk_pertemuan_ke
            CHECK (pertemuan_ke BETWEEN 1 AND 15)
        ");

        DB::statement("
            ALTER TABLE nilai
            ADD CONSTRAINT chk_nilai
            CHECK (nilai BETWEEN 0 AND 100)
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE nilai
            DROP CONSTRAINT IF EXISTS chk_nilai
        ");

        DB::statement("
            ALTER TABLE jadwal
            DROP CONSTRAINT IF EXISTS chk_pertemuan_ke
        ");

        DB::statement("
            ALTER TABLE jadwal
            DROP CONSTRAINT IF EXISTS chk_jam_jadwal
        ");

        DB::statement("
            ALTER TABLE jadwal
            DROP CONSTRAINT IF EXISTS chk_hari_jadwal
        ");
    }
};