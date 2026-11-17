
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'

        -- FUNCTION BIASA

        CREATE OR REPLACE FUNCTION cek_jadwal_tentor(
            p_id_tentor INTEGER,
            p_tanggal DATE,
            p_jam_mulai TIME,
            p_jam_selesai TIME
        )
        RETURNS BOOLEAN
        LANGUAGE plpgsql
        AS $$
        DECLARE
            v_id_kelas INTEGER;
        BEGIN
            SELECT j.id_kelas
            INTO v_id_kelas
            FROM jadwal j
            WHERE j.id_tentor = p_id_tentor
              AND j.tanggal = p_tanggal
              AND j.status = 'terjadwal'
              AND j.jam_mulai < p_jam_selesai
              AND j.jam_selesai > p_jam_mulai
            LIMIT 1;

            IF FOUND THEN
                RAISE EXCEPTION
                    'Jadwal tentor bentrok dengan kelas ID %!',
                    v_id_kelas;
            END IF;

            RETURN TRUE;
        END;
        $$;


        CREATE OR REPLACE FUNCTION hitung_jumlah_siswa_kursus(
            p_id_kursus INTEGER
        )
        RETURNS INTEGER
        LANGUAGE plpgsql
        AS $$
        DECLARE
            jumlah_siswa INTEGER;
        BEGIN
            SELECT COUNT(*)
            INTO jumlah_siswa
            FROM siswa_kursus
            WHERE id_kursus = p_id_kursus
              AND status = 'aktif';

            RETURN jumlah_siswa;
        END;
        $$;


        CREATE OR REPLACE FUNCTION hitung_jumlah_tugas_selesai(
            p_id_siswa INTEGER
        )
        RETURNS INTEGER
        LANGUAGE plpgsql
        AS $$
        DECLARE
            jumlah_selesai INTEGER;
        BEGIN
            SELECT COUNT(pt.id_pengumpulan)
            INTO jumlah_selesai
            FROM pengumpulan_tugas pt
            WHERE pt.id_siswa = p_id_siswa
              AND pt.status IN ('terkumpul', 'terlambat');

            RETURN jumlah_selesai;
        END;
        $$;


        CREATE OR REPLACE FUNCTION hitung_persentase_absensi(
            p_id_siswa INTEGER
        )
        RETURNS NUMERIC
        LANGUAGE plpgsql
        AS $$
        DECLARE
            presentase_absensi NUMERIC;
        BEGIN
            SELECT COALESCE(
                COUNT(
                    CASE
                        WHEN a.status = 'hadir' THEN 1
                    END
                )::NUMERIC
                / NULLIF(COUNT(*), 0) * 100,
                0
            )
            INTO presentase_absensi
            FROM absensi_siswa a
            WHERE a.id_siswa = p_id_siswa;

            RETURN presentase_absensi;
        END;
        $$;


        CREATE OR REPLACE FUNCTION hitung_rata_nilai(
            p_id_siswa INTEGER
        )
        RETURNS NUMERIC
        LANGUAGE plpgsql
        AS $$
        DECLARE
            rata_rata_nilai NUMERIC;
        BEGIN
            SELECT COALESCE(
                SUM(n.nilai) / NULLIF(COUNT(n.nilai), 0),
                0
            )
            INTO rata_rata_nilai
            FROM nilai n
            JOIN pengumpulan_tugas pt
                ON n.id_pengumpulan = pt.id_pengumpulan
            WHERE pt.id_siswa = p_id_siswa;

            RETURN rata_rata_nilai;
        END;
        $$;


        -- TRIGGER FUNCTIONS

        CREATE OR REPLACE FUNCTION trg_validasi_absensi_siswa()
        RETURNS TRIGGER
        LANGUAGE plpgsql
        AS $$
        BEGIN
            IF NOT EXISTS (
                SELECT 1
                FROM jadwal j
                WHERE j.id_jadwal = NEW.id_jadwal
            ) THEN
                RAISE EXCEPTION
                    'Jadwal tidak sesuai!';
            END IF;

            IF NOT EXISTS (
                SELECT 1
                FROM jadwal j
                WHERE j.id_jadwal = NEW.id_jadwal
                  AND j.status <> 'dibatalkan'
            ) THEN
                RAISE EXCEPTION
                    'Jadwal sudah dibatalkan!';
            END IF;

            RETURN NEW;
        END;
        $$;


        CREATE OR REPLACE FUNCTION trg_validasi_absensi_tentor()
        RETURNS TRIGGER
        LANGUAGE plpgsql
        AS $$
        BEGIN
            IF NOT EXISTS (
                SELECT 1
                FROM jadwal j
                WHERE j.id_jadwal = NEW.id_jadwal
            ) THEN
                RAISE EXCEPTION
                    'Jadwal tidak sesuai!';
            END IF;

            IF NOT EXISTS (
                SELECT 1
                FROM jadwal j
                WHERE j.id_jadwal = NEW.id_jadwal
                  AND j.status <> 'dibatalkan'
            ) THEN
                RAISE EXCEPTION
                    'Jadwal sudah dibatalkan!';
            END IF;

            RETURN NEW;
        END;
        $$;


        CREATE OR REPLACE FUNCTION trg_validasi_jadwal()
        RETURNS TRIGGER
        LANGUAGE plpgsql
        AS $$
        BEGIN
            IF NOT EXISTS (
                SELECT 1
                FROM kelas k
                JOIN tentor_kursus tk
                    ON k.id_kursus = tk.id_kursus
                WHERE k.id_kelas = NEW.id_kelas
                  AND tk.id_tentor = NEW.id_tentor
            ) THEN
                RAISE EXCEPTION
                    'Tentor tidak sesuai dengan kursus di jadwal!';
            END IF;

            IF EXISTS (
                SELECT 1
                FROM jadwal j
                WHERE j.id_tentor = NEW.id_tentor
                  AND j.tanggal = NEW.tanggal
                  AND j.id_jadwal <> COALESCE(NEW.id_jadwal, -1)
                  AND NEW.jam_mulai < j.jam_selesai
                  AND NEW.jam_selesai > j.jam_mulai
                  AND j.status <> 'dibatalkan'
                  AND NEW.status <> 'dibatalkan'
            ) THEN
                RAISE EXCEPTION
                    'Jadwal tentor bentrok dengan kelas lain!';
            END IF;

            RETURN NEW;
        END;
        $$;


        CREATE OR REPLACE FUNCTION trg_validasi_pembuat_pengumuman()
        RETURNS TRIGGER
        LANGUAGE plpgsql
        AS $$
        BEGIN
            IF NOT EXISTS (
                SELECT 1
                FROM users
                WHERE id_user = NEW.id_user
                  AND role IN ('admin', 'operator')
            ) THEN
                RAISE EXCEPTION
                    'Hanya admin dan operator yang boleh membuat pengumuman!';
            END IF;

            RETURN NEW;
        END;
        $$;


        CREATE OR REPLACE FUNCTION trg_validasi_pengumpulan_tugas()
        RETURNS TRIGGER
        LANGUAGE plpgsql
        AS $$
        BEGIN
            IF NOT EXISTS (
                SELECT 1
                FROM tugas t
                WHERE t.id_tugas = NEW.id_tugas
            ) THEN
                RAISE EXCEPTION
                    'Tugas tidak ditemukan!';
            END IF;

            IF NOT EXISTS (
                SELECT 1
                FROM tugas t
                JOIN kelas k
                    ON t.id_kelas = k.id_kelas
                WHERE t.id_tugas = NEW.id_tugas
                  AND k.id_siswa = NEW.id_siswa
            ) THEN
                RAISE EXCEPTION
                    'Siswa tidak terdaftar pada kelas tugas ini!';
            END IF;

            RETURN NEW;
        END;
        $$;


        CREATE OR REPLACE FUNCTION trg_validasi_penilaian()
        RETURNS TRIGGER
        LANGUAGE plpgsql
        AS $$
        BEGIN
            IF NOT EXISTS (
                SELECT 1
                FROM pengumpulan_tugas pt
                WHERE pt.id_pengumpulan = NEW.id_pengumpulan
            ) THEN
                RAISE EXCEPTION
                    'Pengumpulan tugas tidak ditemukan!';
            END IF;

            IF EXISTS (
                SELECT 1
                FROM pengumpulan_tugas pt
                WHERE pt.id_pengumpulan = NEW.id_pengumpulan
                  AND pt.status = 'belum_mengumpulkan'
            ) THEN
                RAISE EXCEPTION
                    'Tugas belum dikumpulkan dan belum dapat dinilai!';
            END IF;

            RETURN NEW;
        END;
        $$;


        CREATE OR REPLACE FUNCTION trg_validasi_status_kursus()
        RETURNS TRIGGER
        LANGUAGE plpgsql
        AS $$
        BEGIN
            IF NEW.status = 'aktif' AND EXISTS (
                SELECT 1
                FROM siswa_kursus sk
                WHERE sk.id_siswa = NEW.id_siswa
                  AND sk.id_kursus = NEW.id_kursus
                  AND sk.status = 'aktif'
                  AND sk.id_siswa_kursus
                      IS DISTINCT FROM NEW.id_siswa_kursus
            ) THEN
                RAISE EXCEPTION
                    'Siswa sudah terdaftar aktif pada kursus ini!';
            END IF;

            RETURN NEW;
        END;
        $$;


        CREATE OR REPLACE FUNCTION trg_validasi_tentor_penilai()
        RETURNS TRIGGER
        LANGUAGE plpgsql
        AS $$
        BEGIN
            IF NOT EXISTS (
                SELECT 1
                FROM pengumpulan_tugas pt
                JOIN tugas t
                    ON pt.id_tugas = t.id_tugas
                WHERE pt.id_pengumpulan = NEW.id_pengumpulan
                  AND t.id_tentor = NEW.id_tentor
            ) THEN
                RAISE EXCEPTION
                    'Tentor yang memberikan nilai tidak sesuai!';
            END IF;

            RETURN NEW;
        END;
        $$;

        SQL);
    }

    public function down(): void
    {
        DB::unprepared(<<<'SQL'

        DROP FUNCTION IF EXISTS trg_validasi_tentor_penilai();
        DROP FUNCTION IF EXISTS trg_validasi_status_kursus();
        DROP FUNCTION IF EXISTS trg_validasi_penilaian();
        DROP FUNCTION IF EXISTS trg_validasi_pengumpulan_tugas();
        DROP FUNCTION IF EXISTS trg_validasi_pembuat_pengumuman();
        DROP FUNCTION IF EXISTS trg_validasi_jadwal();
        DROP FUNCTION IF EXISTS trg_validasi_absensi_tentor();
        DROP FUNCTION IF EXISTS trg_validasi_absensi_siswa();

        DROP FUNCTION IF EXISTS hitung_rata_nilai(INTEGER);
        DROP FUNCTION IF EXISTS hitung_persentase_absensi(INTEGER);
        DROP FUNCTION IF EXISTS hitung_jumlah_tugas_selesai(INTEGER);
        DROP FUNCTION IF EXISTS hitung_jumlah_siswa_kursus(INTEGER);
        DROP FUNCTION IF EXISTS cek_jadwal_tentor(INTEGER, DATE, TIME, TIME);

        SQL);
    }
};
