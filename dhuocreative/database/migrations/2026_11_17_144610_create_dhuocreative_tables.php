
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'

        -- SEQUENCES

        CREATE SEQUENCE public.absensi_siswa_id_absensi_seq
            START WITH 1 INCREMENT BY 1 CACHE 1;

        CREATE SEQUENCE public.absensi_tentor_id_absensi_tentor_seq
            START WITH 1 INCREMENT BY 1 CACHE 1;

        CREATE SEQUENCE public.jadwal_id_jadwal_seq
            START WITH 1 INCREMENT BY 1 CACHE 1;

        CREATE SEQUENCE public.nilai_id_nilai_seq
            START WITH 1 INCREMENT BY 1 CACHE 1;

        CREATE SEQUENCE public.pengajuan_jadwal_id_pengajuan_seq
            AS integer START WITH 1 INCREMENT BY 1 CACHE 1;

        CREATE SEQUENCE public.pengumpulan_tugas_id_pengumpulan_seq
            START WITH 1 INCREMENT BY 1 CACHE 1;


        -- TABLE USERS

        CREATE TABLE public.users (
            id_user integer NOT NULL,
            nama varchar(100) NOT NULL,
            email varchar(150) NOT NULL,
            no_telp varchar(255) NOT NULL,
            pasword varchar(255) NOT NULL,
            role public.role_user NOT NULL,
            tahun_masuk integer NOT NULL,
            status public.status_user NOT NULL,
            created_at timestamp without time zone NOT NULL,
            update_at timestamp without time zone NOT NULL
        );


        -- TABLE KURSUS

        CREATE TABLE public.kursus (
            id_kursus integer NOT NULL,
            nama_kursus varchar(100) NOT NULL,
            deskripsi text,
            durasi integer NOT NULL,
            status public.status_kursus NOT NULL,
            created_at timestamp without time zone NOT NULL,
            updated_at timestamp without time zone NOT NULL,
            harga numeric(12,2),
            jumlah_pertemuan integer DEFAULT 15
        );


        -- TABLE SISWA KURSUS

        CREATE TABLE public.siswa_kursus (
            id_siswa_kursus integer NOT NULL,
            id_siswa integer NOT NULL,
            id_kursus integer NOT NULL,
            tanggal_daftar date NOT NULL,
            status public.siswa_kursus_status NOT NULL
        );


        -- TABLE TENTOR KURSUS

        CREATE TABLE public.tentor_kursus (
            id_tentor_kursus integer NOT NULL,
            id_tentor integer NOT NULL,
            id_kursus integer NOT NULL,
            tanggal_daftar date NOT NULL
        );


        -- TABLE KELAS

        CREATE TABLE public.kelas (
            id_kelas integer NOT NULL,
            id_kursus integer NOT NULL,
            status public.status_kelas NOT NULL,
            created_at timestamp without time zone NOT NULL,
            updated_at timestamp without time zone NOT NULL,
            kelas public.jenis_kelas NOT NULL,
            id_siswa integer NOT NULL
        );


        -- TABLE JADWAL

        CREATE TABLE public.jadwal (
            id_jadwal integer NOT NULL DEFAULT nextval('public.jadwal_id_jadwal_seq'::regclass),
            id_kelas integer NOT NULL,
            id_tentor integer NOT NULL,
            tanggal date NOT NULL,
            jam_mulai time without time zone NOT NULL,
            jam_selesai time without time zone NOT NULL,
            status public.status_jadwal NOT NULL,
            pertemuan_ke integer NOT NULL
        );


        -- TABLE ABSENSI SISWA

        CREATE TABLE public.absensi_siswa (
            id_absensi integer NOT NULL DEFAULT nextval('public.absensi_siswa_id_absensi_seq'::regclass),
            id_jadwal integer NOT NULL,
            id_siswa integer NOT NULL,
            waktu_absen timestamp without time zone NOT NULL,
            status public.status_absensi NOT NULL
        );


        -- TABLE ABSENSI TENTOR

        CREATE TABLE public.absensi_tentor (
            id_absensi_tentor integer NOT NULL DEFAULT nextval('public.absensi_tentor_id_absensi_tentor_seq'::regclass),
            id_jadwal integer NOT NULL,
            waktu_absen timestamp without time zone NOT NULL,
            status public.status_absensi NOT NULL
        );


        -- TABLE TUGAS

        CREATE TABLE public.tugas (
            id_tugas integer NOT NULL,
            id_kelas integer NOT NULL,
            id_tentor integer NOT NULL,
            judul varchar(150) NOT NULL,
            deskripsi text NOT NULL,
            deadline timestamp without time zone NOT NULL,
            file_tugas varchar(255) NOT NULL,
            created_at timestamp without time zone NOT NULL,
            update_at timestamp without time zone NOT NULL,
            tipe public.jenis_tugas NOT NULL
        );


        -- TABLE PENGUMPULAN TUGAS

        CREATE TABLE public.pengumpulan_tugas (
            id_pengumpulan integer NOT NULL DEFAULT nextval('public.pengumpulan_tugas_id_pengumpulan_seq'::regclass),
            id_tugas integer NOT NULL,
            id_siswa integer NOT NULL,
            file_jawaban varchar(255) NOT NULL,
            waktu_pengumpulan timestamp without time zone NOT NULL,
            status public.status_pengumpulan_tugas
        );


        -- TABLE NILAI

        CREATE TABLE public.nilai (
            id_nilai integer NOT NULL DEFAULT nextval('public.nilai_id_nilai_seq'::regclass),
            id_pengumpulan integer NOT NULL,
            id_tentor integer NOT NULL,
            nilai numeric(5,2) NOT NULL,
            feedback text,
            tanggal_nilai timestamp without time zone NOT NULL
        );


        -- TABLE SERTIFIKAT

        CREATE TABLE public.sertifikat (
            id_sertifikat integer NOT NULL,
            id_siswa integer NOT NULL,
            id_tentor integer NOT NULL,
            nama_projek varchar(150) NOT NULL,
            tanggal_pengumpulan date NOT NULL,
            file_sertifikat varchar(255) NOT NULL,
            tanggal_upload timestamp without time zone NOT NULL
        );


        -- TABLE PENGUMUMAN

        CREATE TABLE public.pengumuman (
            id_pengumuman integer NOT NULL,
            id_user integer NOT NULL,
            judul varchar(150) NOT NULL,
            isi text NOT NULL,
            tanggal date NOT NULL
        );


        -- TABLE PASSWORD RESET CODES

        CREATE TABLE public.password_reset_codes (
            email varchar(150) NOT NULL,
            code_hash varchar(255) NOT NULL,
            expires_at timestamp without time zone NOT NULL,
            attempts integer DEFAULT 0 NOT NULL,
            created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
        );


        -- TABLE PENGAJUAN JADWAL

        CREATE TABLE public.pengajuan_jadwal (
            id_pengajuan integer NOT NULL DEFAULT nextval('public.pengajuan_jadwal_id_pengajuan_seq'::regclass),
            id_jadwal_lama integer,
            id_kelas integer NOT NULL,
            id_tentor integer NOT NULL,
            tanggal_baru date NOT NULL,
            jam_mulai time without time zone NOT NULL,
            jam_selesai time without time zone NOT NULL,
            alasan text,
            status varchar(20) DEFAULT 'menunggu',
            created_at timestamp without time zone DEFAULT now(),
            updated_at timestamp without time zone DEFAULT now()
        );


        -- SEQUENCE OWNERSHIP

        ALTER SEQUENCE public.absensi_siswa_id_absensi_seq
            OWNED BY public.absensi_siswa.id_absensi;

        ALTER SEQUENCE public.absensi_tentor_id_absensi_tentor_seq
            OWNED BY public.absensi_tentor.id_absensi_tentor;

        ALTER SEQUENCE public.jadwal_id_jadwal_seq
            OWNED BY public.jadwal.id_jadwal;

        ALTER SEQUENCE public.nilai_id_nilai_seq
            OWNED BY public.nilai.id_nilai;

        ALTER SEQUENCE public.pengajuan_jadwal_id_pengajuan_seq
            OWNED BY public.pengajuan_jadwal.id_pengajuan;

        ALTER SEQUENCE public.pengumpulan_tugas_id_pengumpulan_seq
            OWNED BY public.pengumpulan_tugas.id_pengumpulan;

        SQL);
    }

    public function down(): void
    {
        DB::unprepared(<<<'SQL'

        DROP TABLE IF EXISTS
            public.pengajuan_jadwal,
            public.password_reset_codes,
            public.pengumuman,
            public.sertifikat,
            public.nilai,
            public.pengumpulan_tugas,
            public.tugas,
            public.absensi_tentor,
            public.absensi_siswa,
            public.jadwal,
            public.kelas,
            public.tentor_kursus,
            public.siswa_kursus,
            public.kursus,
            public.users;

        DROP SEQUENCE IF EXISTS
            public.pengajuan_jadwal_id_pengajuan_seq,
            public.pengumpulan_tugas_id_pengumpulan_seq,
            public.nilai_id_nilai_seq,
            public.jadwal_id_jadwal_seq,
            public.absensi_tentor_id_absensi_tentor_seq,
            public.absensi_siswa_id_absensi_seq;

        SQL);
    }
};
