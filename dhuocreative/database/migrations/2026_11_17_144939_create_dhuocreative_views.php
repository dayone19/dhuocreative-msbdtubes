
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'

        CREATE VIEW vw_detail_kelas AS
        SELECT DISTINCT
            kl.id_kelas,
            ku.nama_kursus AS nama_kelas,
            ns.nama AS nama_siswa,
            nt.nama AS nama_tentor,
            kl.kelas,
            kl.status,
            kl.created_at AS dibuat_pada
        FROM kelas kl
        JOIN kursus ku ON kl.id_kursus = ku.id_kursus
        JOIN users ns ON kl.id_siswa = ns.id_user
        LEFT JOIN jadwal j ON kl.id_kelas = j.id_kelas
        LEFT JOIN users nt ON j.id_tentor = nt.id_user;

        CREATE OR REPLACE VIEW public.vw_jadwal_tentor AS
        SELECT
            u.nama AS nama_tentor,
            k.nama_kursus AS kelas,
            j.tanggal,
            j.jam_mulai,
            j.jam_selesai,
            j.status,
            j.id_jadwal,
            j.id_tentor,
            ks.id_kelas,
            ks.kelas::text AS jenis_kelas,
            s.nama AS siswa
        FROM jadwal j
        JOIN users u ON j.id_tentor = u.id_user
        JOIN kelas ks ON j.id_kelas = ks.id_kelas
        JOIN kursus k ON ks.id_kursus = k.id_kursus
        JOIN users s ON ks.id_siswa = s.id_user;

        CREATE VIEW vw_nilai_siswa AS
        SELECT
            u.nama AS nama_siswa,
            t.judul AS judul_tugas,
            k.nama_kursus AS kelas,
            n.nilai
        FROM nilai n
        JOIN pengumpulan_tugas pt ON n.id_pengumpulan = pt.id_pengumpulan
        JOIN users u ON pt.id_siswa = u.id_user
        JOIN tugas t ON pt.id_tugas = t.id_tugas
        JOIN kelas ks ON t.id_kelas = ks.id_kelas
        JOIN kursus k ON ks.id_kursus = k.id_kursus;

        CREATE VIEW vw_progres_siswa AS
        SELECT
            u.nama AS nama_siswa,
            k.nama_kursus,
            sk.status AS status_kursus,

            (
                SELECT COUNT(*)
                FROM tugas t
                JOIN kelas ks ON t.id_kelas = ks.id_kelas
                WHERE ks.id_siswa = sk.id_siswa
                AND ks.id_kursus = sk.id_kursus
            ) AS jumlah_tugas,

            (
                SELECT COUNT(*)
                FROM tugas t
                JOIN kelas ks ON t.id_kelas = ks.id_kelas
                JOIN pengumpulan_tugas pt ON t.id_tugas = pt.id_tugas
                WHERE ks.id_siswa = sk.id_siswa
                AND ks.id_kursus = sk.id_kursus
                AND pt.status IN ('terkumpul', 'terlambat')
            ) AS jumlah_tugas_selesai,

            (
                SELECT COUNT(*)
                FROM tugas t
                JOIN kelas ks ON t.id_kelas = ks.id_kelas
                LEFT JOIN pengumpulan_tugas pt
                    ON t.id_tugas = pt.id_tugas
                    AND pt.id_siswa = sk.id_siswa
                WHERE ks.id_siswa = sk.id_siswa
                AND ks.id_kursus = sk.id_kursus
                AND (
                    pt.id_pengumpulan IS NULL
                    OR pt.status = 'belum_mengumpulkan'
                )
            ) AS jumlah_tugas_belum,

            (
                SELECT COALESCE(AVG(n.nilai), 0)
                FROM tugas t
                JOIN kelas ks ON t.id_kelas = ks.id_kelas
                JOIN pengumpulan_tugas pt ON t.id_tugas = pt.id_tugas
                JOIN nilai n ON pt.id_pengumpulan = n.id_pengumpulan
                WHERE ks.id_siswa = sk.id_siswa
                AND ks.id_kursus = sk.id_kursus
            ) AS rata_rata_nilai,

            (
                SELECT COALESCE(
                    (
                        COUNT(*) FILTER (WHERE a.status = 'hadir')::numeric
                        / NULLIF(COUNT(*), 0)::numeric
                    ) * 100,
                    0
                )
                FROM absensi_siswa a
                JOIN jadwal j ON a.id_jadwal = j.id_jadwal
                JOIN kelas ks ON j.id_kelas = ks.id_kelas
                WHERE ks.id_siswa = sk.id_siswa
                AND ks.id_kursus = sk.id_kursus
            ) AS persentase_absensi

        FROM siswa_kursus sk
        JOIN users u ON sk.id_siswa = u.id_user
        JOIN kursus k ON sk.id_kursus = k.id_kursus;

        CREATE VIEW vw_rekap_absensi_siswa AS
        SELECT
            u.nama AS nama_siswa,
            ku.nama_kursus AS kelas,
            j.tanggal,
            ab.status
        FROM absensi_siswa ab
        JOIN users u ON ab.id_siswa = u.id_user
        JOIN jadwal j ON ab.id_jadwal = j.id_jadwal
        JOIN kelas k ON j.id_kelas = k.id_kelas
        JOIN kursus ku ON k.id_kursus = ku.id_kursus;

        CREATE VIEW vw_rekap_absensi_tentor AS
        SELECT
            u.nama AS nama_tentor,
            ku.nama_kursus AS kelas,
            j.tanggal,
            ab.status
        FROM absensi_tentor ab
        JOIN jadwal j ON ab.id_jadwal = j.id_jadwal
        JOIN users u ON j.id_tentor = u.id_user
        JOIN kelas k ON j.id_kelas = k.id_kelas
        JOIN kursus ku ON k.id_kursus = ku.id_kursus;

        CREATE VIEW vw_rekap_tugas AS
        SELECT
            nt.nama AS nama_tentor,
            k.nama_kursus AS kelas,
            t.judul AS judul_tugas,
            t.deadline,
            t.tipe AS tipe_tugas,
            ns.nama AS nama_siswa,
            pt.status
        FROM tugas t
        JOIN users nt ON t.id_tentor = nt.id_user
        JOIN kelas ks ON t.id_kelas = ks.id_kelas
        JOIN kursus k ON ks.id_kursus = k.id_kursus
        JOIN users ns ON ks.id_siswa = ns.id_user
        LEFT JOIN pengumpulan_tugas pt ON t.id_tugas = pt.id_tugas;

        CREATE VIEW vw_semua_kelas AS
        SELECT DISTINCT
            k.nama_kursus,
            ut.nama AS nama_tentor,
            us.nama AS nama_siswa,
            ks.status,
            ks.kelas
        FROM kelas ks
        JOIN kursus k ON ks.id_kursus = k.id_kursus
        JOIN users us ON ks.id_siswa = us.id_user
        LEFT JOIN jadwal j ON ks.id_kelas = j.id_kelas
        LEFT JOIN users ut ON j.id_tentor = ut.id_user;

        CREATE VIEW vw_semua_kursus AS
        SELECT
            nama_kursus,
            status,
            harga
        FROM kursus k;

        CREATE VIEW vw_semua_operator AS
        SELECT
            nama,
            email,
            no_telp,
            status,
            tahun_masuk
        FROM users u
        WHERE role = 'operator';

        CREATE VIEW vw_semua_pengumuman AS
        SELECT
            p.id_pengumuman,
            p.judul,
            p.isi,
            p.tanggal,
            u.nama,
            u.role
        FROM pengumuman p
        JOIN users u ON p.id_user = u.id_user;

        CREATE VIEW vw_semua_siswa AS
        SELECT
            nama,
            email,
            no_telp,
            status,
            tahun_masuk
        FROM users u
        WHERE role = 'siswa';

        CREATE VIEW vw_semua_tentor AS
        SELECT
            nama,
            email,
            no_telp,
            status,
            tahun_masuk
        FROM users u
        WHERE role = 'tentor';

        CREATE VIEW vw_siswa_kursus AS
        SELECT
            u.nama,
            k.nama_kursus,
            sk.tanggal_daftar,
            sk.status
        FROM siswa_kursus sk
        JOIN kursus k ON sk.id_kursus = k.id_kursus
        JOIN users u ON sk.id_siswa = u.id_user;

        CREATE VIEW public.vw_rekap_absensi_bulanan AS
            SELECT
                k.id_siswa,
                k.id_kursus,
                j.id_tentor,
                DATE_TRUNC('month', j.tanggal)::date AS periode,
                u.nama AS nama_siswa,
                ku.nama_kursus AS kursus,
                k.kelas AS jenis_kelas,
                t.nama AS nama_tentor,
                COUNT(j.id_jadwal) AS total_pertemuan,
                COUNT(j.id_jadwal) FILTER (
                    WHERE a.status = 'hadir'
                ) AS hadir,
                COUNT(j.id_jadwal) FILTER (
                    WHERE a.status = 'izin'
                ) AS izin,
                COUNT(j.id_jadwal) FILTER (
                    WHERE a.status = 'sakit'
                ) AS sakit,
                COUNT(j.id_jadwal) FILTER (
                    WHERE a.status = 'alpha'
                ) AS alpha,
                COALESCE(
                    ROUND(
                        COUNT(j.id_jadwal) FILTER (
                            WHERE a.status = 'hadir'
                        )::numeric
                        / NULLIF(COUNT(j.id_jadwal), 0) * 100,
                        2
                    ),
                    0
                ) AS persentase_kehadiran
            FROM kelas k
            JOIN users u
                ON k.id_siswa = u.id_user
            JOIN kursus ku
                ON k.id_kursus = ku.id_kursus
            JOIN jadwal j
                ON k.id_kelas = j.id_kelas
            JOIN users t
                ON j.id_tentor = t.id_user
            LEFT JOIN absensi_siswa a
                ON j.id_jadwal = a.id_jadwal
                AND k.id_siswa = a.id_siswa
            WHERE j.status = 'selesai'
            GROUP BY
                k.id_siswa,
                k.id_kursus,
                j.id_tentor,
                DATE_TRUNC('month', j.tanggal),
                u.nama,
                ku.nama_kursus,
                k.kelas,
                t.nama;
        SQL);
        
    }

    public function down(): void
    {
        DB::unprepared(<<<'SQL'

        DROP VIEW IF EXISTS vw_siswa_kursus;
        DROP VIEW IF EXISTS vw_semua_tentor;
        DROP VIEW IF EXISTS vw_semua_siswa;
        DROP VIEW IF EXISTS vw_semua_pengumuman;
        DROP VIEW IF EXISTS vw_semua_operator;
        DROP VIEW IF EXISTS vw_semua_kursus;
        DROP VIEW IF EXISTS vw_semua_kelas;
        DROP VIEW IF EXISTS vw_rekap_tugas;
        DROP VIEW IF EXISTS vw_rekap_absensi_tentor;
        DROP VIEW IF EXISTS vw_rekap_absensi_siswa;
        DROP VIEW IF EXISTS vw_progres_siswa;
        DROP VIEW IF EXISTS vw_nilai_siswa;
        DROP VIEW IF EXISTS vw_jadwal_tentor;
        DROP VIEW IF EXISTS vw_detail_kelas;

        SQL);
    }
};
