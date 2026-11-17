
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'

        -- PROCEDURE 1: CATAT ABSENSI SISWA

        CREATE OR REPLACE PROCEDURE catat_absensi_siswa(
            IN p_id_jadwal INTEGER,
            IN p_id_siswa INTEGER,
            IN p_status public.status_absensi
        )
        LANGUAGE plpgsql
        AS $$
        BEGIN
            INSERT INTO public.absensi_siswa(
                id_jadwal,
                id_siswa,
                waktu_absen,
                status
            )
            VALUES(
                p_id_jadwal,
                p_id_siswa,
                CURRENT_TIMESTAMP,
                p_status
            );
        END;
        $$;


        -- PROCEDURE 2: CATAT ABSENSI TENTOR

        CREATE OR REPLACE PROCEDURE catat_absensi_tentor(
            IN p_id_jadwal INTEGER,
            IN p_status public.status_absensi
        )
        LANGUAGE plpgsql
        AS $$
        BEGIN
            INSERT INTO public.absensi_tentor(
                id_jadwal,
                waktu_absen,
                status
            )
            VALUES(
                p_id_jadwal,
                CURRENT_TIMESTAMP,
                p_status
            );
        END;
        $$;


        -- PROCEDURE 3: FINALISASI NILAI KURSUS

        CREATE OR REPLACE PROCEDURE finalisasi_nilai_kursus(
            IN p_id_siswa INTEGER,
            IN p_id_kursus INTEGER
        )
        LANGUAGE plpgsql
        AS $$
        DECLARE
            v_jumlah_tugas INTEGER;
            v_jumlah_dinilai INTEGER;
            v_nilai_akhir NUMERIC;
        BEGIN
            SELECT COUNT(t.id_tugas),
                   COUNT(n.id_nilai)
            INTO v_jumlah_tugas, v_jumlah_dinilai
            FROM public.kelas k
            JOIN public.tugas t
                ON k.id_kelas = t.id_kelas
            LEFT JOIN public.pengumpulan_tugas pt
                ON t.id_tugas = pt.id_tugas
               AND pt.id_siswa = p_id_siswa
            LEFT JOIN public.nilai n
                ON pt.id_pengumpulan = n.id_pengumpulan
            WHERE k.id_siswa = p_id_siswa
              AND k.id_kursus = p_id_kursus;

            IF v_jumlah_tugas = 0 THEN
                RAISE EXCEPTION
                    'Tidak ada tugas untuk kursus ini!';
            END IF;

            IF v_jumlah_dinilai <> v_jumlah_tugas THEN
                RAISE EXCEPTION
                    'Masih ada tugas yang belum dikumpulkan atau dinilai!';
            END IF;

            SELECT AVG(n.nilai)
            INTO v_nilai_akhir
            FROM public.kelas k
            JOIN public.tugas t
                ON k.id_kelas = t.id_kelas
            JOIN public.pengumpulan_tugas pt
                ON t.id_tugas = pt.id_tugas
            JOIN public.nilai n
                ON pt.id_pengumpulan = n.id_pengumpulan
            WHERE k.id_siswa = p_id_siswa
              AND k.id_kursus = p_id_kursus;

            RAISE NOTICE 'Finalisasi berhasil. Nilai akhir: %',
                v_nilai_akhir;
        END;
        $$;


        -- PROCEDURE 4: INPUT NILAI

        CREATE OR REPLACE PROCEDURE input_nilai(
            IN p_id_pengumpulan INTEGER,
            IN p_id_tentor INTEGER,
            IN p_nilai NUMERIC,
            IN p_feedback TEXT
        )
        LANGUAGE plpgsql
        AS $$
        BEGIN
            INSERT INTO public.nilai(
                id_pengumpulan,
                id_tentor,
                nilai,
                feedback,
                tanggal_nilai
            )
            VALUES(
                p_id_pengumpulan,
                p_id_tentor,
                p_nilai,
                p_feedback,
                CURRENT_DATE
            );
        END;
        $$;


        -- PROCEDURE 5: UBAH STATUS KURSUS SISWA

        CREATE OR REPLACE PROCEDURE ubah_status_kursus_siswa(
            IN p_id_siswa_kursus INTEGER,
            IN p_status_baru public.siswa_kursus_status
        )
        LANGUAGE plpgsql
        AS $$
        BEGIN
            UPDATE public.siswa_kursus
            SET status = p_status_baru
            WHERE id_siswa_kursus = p_id_siswa_kursus;
        END;
        $$;

        SQL);
    }

    public function down(): void
    {
        DB::unprepared(<<<'SQL'

        DROP PROCEDURE IF EXISTS ubah_status_kursus_siswa(
            INTEGER,
            public.siswa_kursus_status
        );

        DROP PROCEDURE IF EXISTS input_nilai(
            INTEGER,
            INTEGER,
            NUMERIC,
            TEXT
        );

        DROP PROCEDURE IF EXISTS finalisasi_nilai_kursus(
            INTEGER,
            INTEGER
        );

        DROP PROCEDURE IF EXISTS catat_absensi_tentor(
            INTEGER,
            public.status_absensi
        );

        DROP PROCEDURE IF EXISTS catat_absensi_siswa(
            INTEGER,
            INTEGER,
            public.status_absensi
        );

        SQL);
    }
};
