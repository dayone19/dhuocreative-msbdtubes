--
-- PostgreSQL database dump
--

\restrict aoIUtOlGeeMd2AOjQcuQEUOOnQA3jotXht02gjgGMfEVln76eApxsorYKJzt3ov

-- Dumped from database version 18.6
-- Dumped by pg_dump version 18.6

-- Started on 2026-10-11 19:58:43

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 866 (class 1247 OID 16505)
-- Name: role_user; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.role_user AS ENUM (
    'admin',
    'operator',
    'tentor',
    'siswa'
);


ALTER TYPE public.role_user OWNER TO postgres;

--
-- TOC entry 887 (class 1247 OID 16605)
-- Name: siswa_kelas_status; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.siswa_kelas_status AS ENUM (
    'aktif',
    'selesai',
    'keluar'
);


ALTER TYPE public.siswa_kelas_status OWNER TO postgres;

--
-- TOC entry 893 (class 1247 OID 16632)
-- Name: siswa_kursus_status; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.siswa_kursus_status AS ENUM (
    'aktif',
    'selesai',
    'keluar'
);


ALTER TYPE public.siswa_kursus_status OWNER TO postgres;

--
-- TOC entry 908 (class 1247 OID 16722)
-- Name: status_absensi; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.status_absensi AS ENUM (
    'hadir',
    'izin',
    'sakit',
    'alpha'
);


ALTER TYPE public.status_absensi OWNER TO postgres;

--
-- TOC entry 902 (class 1247 OID 16693)
-- Name: status_jadwal; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.status_jadwal AS ENUM (
    'terjadwal',
    'selesai',
    'dibatalkan'
);


ALTER TYPE public.status_jadwal OWNER TO postgres;

--
-- TOC entry 881 (class 1247 OID 16557)
-- Name: status_kelas; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.status_kelas AS ENUM (
    'aktif',
    'selesai',
    'nonaktif'
);


ALTER TYPE public.status_kelas OWNER TO postgres;

--
-- TOC entry 875 (class 1247 OID 16539)
-- Name: status_kursus; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.status_kursus AS ENUM (
    'aktif',
    'nonaktif'
);


ALTER TYPE public.status_kursus OWNER TO postgres;

--
-- TOC entry 920 (class 1247 OID 16834)
-- Name: status_pengumpulan_tugas; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.status_pengumpulan_tugas AS ENUM (
    'kumpul',
    'terlambat',
    'tidak_kumpul'
);


ALTER TYPE public.status_pengumpulan_tugas OWNER TO postgres;

--
-- TOC entry 932 (class 1247 OID 16968)
-- Name: status_pengumuman; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.status_pengumuman AS ENUM (
    'aktif',
    'nonaktif'
);


ALTER TYPE public.status_pengumuman OWNER TO postgres;

--
-- TOC entry 869 (class 1247 OID 16514)
-- Name: status_user; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.status_user AS ENUM (
    'aktif',
    'nonaktif'
);


ALTER TYPE public.status_user OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 226 (class 1259 OID 16751)
-- Name: absensi_siswa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.absensi_siswa (
    id_absensi integer NOT NULL,
    id_jadwal integer NOT NULL,
    id_siswa integer NOT NULL,
    waktu_absen timestamp without time zone NOT NULL,
    status public.status_absensi NOT NULL
);


ALTER TABLE public.absensi_siswa OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 16791)
-- Name: absensi_tentor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.absensi_tentor (
    id_absensi_tentor integer NOT NULL,
    id_jadwal integer NOT NULL,
    waktu_absen timestamp without time zone NOT NULL,
    status public.status_absensi NOT NULL
);


ALTER TABLE public.absensi_tentor OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 16699)
-- Name: jadwal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jadwal (
    id_jadwal integer NOT NULL,
    id_kelas integer NOT NULL,
    id_tentor integer NOT NULL,
    tanggal date NOT NULL,
    jam_mulai time without time zone NOT NULL,
    jam_selesai time without time zone NOT NULL,
    status public.status_jadwal NOT NULL
);


ALTER TABLE public.jadwal OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16587)
-- Name: kelas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.kelas (
    id_kelas integer NOT NULL,
    id_kursus integer NOT NULL,
    nama_kelas character varying(100) NOT NULL,
    kapasitas integer NOT NULL,
    status public.status_kelas NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.kelas OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 16543)
-- Name: kursus; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.kursus (
    id_kursus integer NOT NULL,
    nama_kursus character varying(100) NOT NULL,
    deskripsi text,
    durasi integer NOT NULL,
    status public.status_kursus NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.kursus OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16922)
-- Name: nilai; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.nilai (
    id_nilai integer NOT NULL,
    id_pengumpulan integer NOT NULL,
    id_tentor integer NOT NULL,
    nilai numeric(5,2) NOT NULL,
    feedback text,
    tanggal_nilai timestamp without time zone NOT NULL
);


ALTER TABLE public.nilai OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 16994)
-- Name: password_reset_codes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_codes (
    email character varying(150) NOT NULL,
    code_hash character varying(255) NOT NULL,
    expires_at timestamp without time zone NOT NULL,
    attempts integer DEFAULT 0 NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.password_reset_codes OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 16902)
-- Name: pengumpulan_tugas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pengumpulan_tugas (
    id_pengumpulan integer NOT NULL,
    id_tugas integer NOT NULL,
    id_siswa integer NOT NULL,
    file_jawaban character varying(255) NOT NULL,
    waktu_pengumpulan timestamp without time zone NOT NULL,
    stauts public.status_pengumpulan_tugas
);


ALTER TABLE public.pengumpulan_tugas OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 16973)
-- Name: pengumuman; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pengumuman (
    id_pengumuman integer NOT NULL,
    id_user integer NOT NULL,
    judul character varying(150) NOT NULL,
    isi text NOT NULL,
    tanggal date NOT NULL
);


ALTER TABLE public.pengumuman OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 16944)
-- Name: sertifikat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sertifikat (
    id_sertifikat integer NOT NULL,
    id_siswa integer NOT NULL,
    id_tentor integer NOT NULL,
    nama_ujian character varying(150) NOT NULL,
    tanggal_ujian date NOT NULL,
    file_sertifikat character varying(255) NOT NULL,
    tanggal_upload timestamp without time zone NOT NULL
);


ALTER TABLE public.sertifikat OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 16611)
-- Name: siswa_kelas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.siswa_kelas (
    id_siswa_kelas integer NOT NULL,
    id_siswa integer NOT NULL,
    id_kelas integer NOT NULL,
    tanggal_daftar date NOT NULL,
    status public.siswa_kelas_status NOT NULL
);


ALTER TABLE public.siswa_kelas OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16639)
-- Name: siswa_kursus; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.siswa_kursus (
    id_siswa_kursus integer NOT NULL,
    id_siswa integer NOT NULL,
    id_kursus integer NOT NULL,
    tanggal_daftar date NOT NULL,
    status public.siswa_kursus_status NOT NULL
);


ALTER TABLE public.siswa_kursus OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 16673)
-- Name: tentor_kursus; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tentor_kursus (
    id_tentor_kursus integer NOT NULL,
    id_tentor integer NOT NULL,
    id_kursus integer NOT NULL,
    tanggal_daftar date NOT NULL
);


ALTER TABLE public.tentor_kursus OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 16807)
-- Name: tugas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tugas (
    id_tugas integer NOT NULL,
    id_kelas integer NOT NULL,
    id_tentor integer NOT NULL,
    judul character varying(150) NOT NULL,
    deskripsi text NOT NULL,
    deadline timestamp without time zone NOT NULL,
    file_tugas character varying(255) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    update_at timestamp without time zone NOT NULL
);


ALTER TABLE public.tugas OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16519)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id_user integer NOT NULL,
    nama character varying(100) NOT NULL,
    email character varying(150) NOT NULL,
    no_telp character varying(255) NOT NULL,
    pasword character varying(255) NOT NULL,
    role public.role_user NOT NULL,
    tahun_masuk integer NOT NULL,
    status public.status_user NOT NULL,
    created_at timestamp without time zone NOT NULL,
    update_at timestamp without time zone NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 5152 (class 0 OID 16751)
-- Dependencies: 226
-- Data for Name: absensi_siswa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.absensi_siswa (id_absensi, id_jadwal, id_siswa, waktu_absen, status) FROM stdin;
\.


--
-- TOC entry 5153 (class 0 OID 16791)
-- Dependencies: 227
-- Data for Name: absensi_tentor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.absensi_tentor (id_absensi_tentor, id_jadwal, waktu_absen, status) FROM stdin;
\.


--
-- TOC entry 5151 (class 0 OID 16699)
-- Dependencies: 225
-- Data for Name: jadwal; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jadwal (id_jadwal, id_kelas, id_tentor, tanggal, jam_mulai, jam_selesai, status) FROM stdin;
\.


--
-- TOC entry 5147 (class 0 OID 16587)
-- Dependencies: 221
-- Data for Name: kelas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.kelas (id_kelas, id_kursus, nama_kelas, kapasitas, status, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5146 (class 0 OID 16543)
-- Dependencies: 220
-- Data for Name: kursus; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.kursus (id_kursus, nama_kursus, deskripsi, durasi, status, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5156 (class 0 OID 16922)
-- Dependencies: 230
-- Data for Name: nilai; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.nilai (id_nilai, id_pengumpulan, id_tentor, nilai, feedback, tanggal_nilai) FROM stdin;
\.


--
-- TOC entry 5159 (class 0 OID 16994)
-- Dependencies: 233
-- Data for Name: password_reset_codes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_codes (email, code_hash, expires_at, attempts, created_at) FROM stdin;
\.


--
-- TOC entry 5155 (class 0 OID 16902)
-- Dependencies: 229
-- Data for Name: pengumpulan_tugas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pengumpulan_tugas (id_pengumpulan, id_tugas, id_siswa, file_jawaban, waktu_pengumpulan, stauts) FROM stdin;
\.


--
-- TOC entry 5158 (class 0 OID 16973)
-- Dependencies: 232
-- Data for Name: pengumuman; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pengumuman (id_pengumuman, id_user, judul, isi, tanggal) FROM stdin;
\.


--
-- TOC entry 5157 (class 0 OID 16944)
-- Dependencies: 231
-- Data for Name: sertifikat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sertifikat (id_sertifikat, id_siswa, id_tentor, nama_ujian, tanggal_ujian, file_sertifikat, tanggal_upload) FROM stdin;
\.


--
-- TOC entry 5148 (class 0 OID 16611)
-- Dependencies: 222
-- Data for Name: siswa_kelas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.siswa_kelas (id_siswa_kelas, id_siswa, id_kelas, tanggal_daftar, status) FROM stdin;
\.


--
-- TOC entry 5149 (class 0 OID 16639)
-- Dependencies: 223
-- Data for Name: siswa_kursus; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.siswa_kursus (id_siswa_kursus, id_siswa, id_kursus, tanggal_daftar, status) FROM stdin;
\.


--
-- TOC entry 5150 (class 0 OID 16673)
-- Dependencies: 224
-- Data for Name: tentor_kursus; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tentor_kursus (id_tentor_kursus, id_tentor, id_kursus, tanggal_daftar) FROM stdin;
\.


--
-- TOC entry 5154 (class 0 OID 16807)
-- Dependencies: 228
-- Data for Name: tugas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tugas (id_tugas, id_kelas, id_tentor, judul, deskripsi, deadline, file_tugas, created_at, update_at) FROM stdin;
\.


--
-- TOC entry 5145 (class 0 OID 16519)
-- Dependencies: 219
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id_user, nama, email, no_telp, pasword, role, tahun_masuk, status, created_at, update_at) FROM stdin;
1	Administrator	admin@dhuocreative.com	081234567801	$2y$12$hombYajKb2dHoeHTCm04fuvQ.ZhrEriDONN88EmIMJ1C7PNHTBIjm	admin	2026	aktif	2026-10-09 14:06:48	2026-10-09 14:06:48
2	Operator	operator@dhuocreative.com	081234567802	$2y$12$ovVoezK/vRSgernkQXm5QuTIgNRLZUIGPL7r1.iGN1fc2K.0S0G/2	operator	2026	aktif	2026-10-09 14:06:48	2026-10-09 14:06:48
3	Tentor	tentor@dhuocreative.com	081234567803	$2y$12$zLNR1wjZkN3YxZOhZ6PL9.ZEZfIyHSjzbI9XusOW6XsSKGgG0szdO	tentor	2026	aktif	2026-10-09 14:06:48	2026-10-09 14:06:48
4	Siswa	siswa@dhuocreative.com	081234567804	$2y$12$EkxkykCKOAR0.rBV5.1/1.eH9EyXwz7M1YvKNMaew234UZkvHI83G	siswa	2026	aktif	2026-10-09 14:06:48	2026-10-09 14:06:48
5	Deyoan	deyoansalsabila43@gmail.com	081375075091	$2y$12$Ig2VJ7baoMQ2cnyG.lluGuDGAg7VayCdSytap/nYksU1KQ0.dFkaW	tentor	2026	aktif	2026-10-10 07:14:26	2026-10-10 08:35:36
\.


--
-- TOC entry 4960 (class 2606 OID 16760)
-- Name: absensi_siswa absensi_siswa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.absensi_siswa
    ADD CONSTRAINT absensi_siswa_pkey PRIMARY KEY (id_absensi);


--
-- TOC entry 4962 (class 2606 OID 16801)
-- Name: absensi_tentor absensi_tentor_id_jadwal_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.absensi_tentor
    ADD CONSTRAINT absensi_tentor_id_jadwal_key UNIQUE (id_jadwal);


--
-- TOC entry 4964 (class 2606 OID 16799)
-- Name: absensi_tentor absensi_tentor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.absensi_tentor
    ADD CONSTRAINT absensi_tentor_pkey PRIMARY KEY (id_absensi_tentor);


--
-- TOC entry 4958 (class 2606 OID 16710)
-- Name: jadwal jadwal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jadwal
    ADD CONSTRAINT jadwal_pkey PRIMARY KEY (id_jadwal);


--
-- TOC entry 4950 (class 2606 OID 16598)
-- Name: kelas kelas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kelas
    ADD CONSTRAINT kelas_pkey PRIMARY KEY (id_kelas);


--
-- TOC entry 4948 (class 2606 OID 16555)
-- Name: kursus kursus_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kursus
    ADD CONSTRAINT kursus_pkey PRIMARY KEY (id_kursus);


--
-- TOC entry 4970 (class 2606 OID 16933)
-- Name: nilai nilai_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nilai
    ADD CONSTRAINT nilai_pkey PRIMARY KEY (id_nilai);


--
-- TOC entry 4976 (class 2606 OID 17005)
-- Name: password_reset_codes password_reset_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_codes
    ADD CONSTRAINT password_reset_codes_pkey PRIMARY KEY (email);


--
-- TOC entry 4968 (class 2606 OID 16911)
-- Name: pengumpulan_tugas pengumpulan_tugas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pengumpulan_tugas
    ADD CONSTRAINT pengumpulan_tugas_pkey PRIMARY KEY (id_pengumpulan);


--
-- TOC entry 4974 (class 2606 OID 16984)
-- Name: pengumuman pengumuman_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pengumuman
    ADD CONSTRAINT pengumuman_pkey PRIMARY KEY (id_pengumuman);


--
-- TOC entry 4972 (class 2606 OID 16955)
-- Name: sertifikat sertifikat_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sertifikat
    ADD CONSTRAINT sertifikat_pkey PRIMARY KEY (id_sertifikat);


--
-- TOC entry 4952 (class 2606 OID 16620)
-- Name: siswa_kelas siswa_kelas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.siswa_kelas
    ADD CONSTRAINT siswa_kelas_pkey PRIMARY KEY (id_siswa_kelas);


--
-- TOC entry 4954 (class 2606 OID 16648)
-- Name: siswa_kursus siswa_kursus_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.siswa_kursus
    ADD CONSTRAINT siswa_kursus_pkey PRIMARY KEY (id_siswa_kursus);


--
-- TOC entry 4956 (class 2606 OID 16681)
-- Name: tentor_kursus tentor_kursus_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tentor_kursus
    ADD CONSTRAINT tentor_kursus_pkey PRIMARY KEY (id_tentor_kursus);


--
-- TOC entry 4966 (class 2606 OID 16822)
-- Name: tugas tugas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tugas
    ADD CONSTRAINT tugas_pkey PRIMARY KEY (id_tugas);


--
-- TOC entry 4944 (class 2606 OID 16537)
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- TOC entry 4946 (class 2606 OID 16535)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id_user);


--
-- TOC entry 4986 (class 2606 OID 16761)
-- Name: absensi_siswa absensi_siswa_id_jadwal_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.absensi_siswa
    ADD CONSTRAINT absensi_siswa_id_jadwal_fkey FOREIGN KEY (id_jadwal) REFERENCES public.jadwal(id_jadwal);


--
-- TOC entry 4987 (class 2606 OID 16766)
-- Name: absensi_siswa absensi_siswa_id_siswa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.absensi_siswa
    ADD CONSTRAINT absensi_siswa_id_siswa_fkey FOREIGN KEY (id_siswa) REFERENCES public.users(id_user);


--
-- TOC entry 4988 (class 2606 OID 16802)
-- Name: absensi_tentor absensi_tentor_id_jadwal_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.absensi_tentor
    ADD CONSTRAINT absensi_tentor_id_jadwal_fkey FOREIGN KEY (id_jadwal) REFERENCES public.jadwal(id_jadwal);


--
-- TOC entry 4984 (class 2606 OID 16711)
-- Name: jadwal jadwal_id_kelas_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jadwal
    ADD CONSTRAINT jadwal_id_kelas_fkey FOREIGN KEY (id_kelas) REFERENCES public.kelas(id_kelas);


--
-- TOC entry 4985 (class 2606 OID 16716)
-- Name: jadwal jadwal_id_tentor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jadwal
    ADD CONSTRAINT jadwal_id_tentor_fkey FOREIGN KEY (id_tentor) REFERENCES public.users(id_user);


--
-- TOC entry 4977 (class 2606 OID 16599)
-- Name: kelas kelas_id_kursus_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kelas
    ADD CONSTRAINT kelas_id_kursus_fkey FOREIGN KEY (id_kursus) REFERENCES public.kursus(id_kursus);


--
-- TOC entry 4993 (class 2606 OID 16934)
-- Name: nilai nilai_id_pengumpulan_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nilai
    ADD CONSTRAINT nilai_id_pengumpulan_fkey FOREIGN KEY (id_pengumpulan) REFERENCES public.pengumpulan_tugas(id_pengumpulan);


--
-- TOC entry 4994 (class 2606 OID 16939)
-- Name: nilai nilai_id_tentor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nilai
    ADD CONSTRAINT nilai_id_tentor_fkey FOREIGN KEY (id_tentor) REFERENCES public.users(id_user);


--
-- TOC entry 4991 (class 2606 OID 16917)
-- Name: pengumpulan_tugas pengumpulan_tugas_id_siswa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pengumpulan_tugas
    ADD CONSTRAINT pengumpulan_tugas_id_siswa_fkey FOREIGN KEY (id_siswa) REFERENCES public.users(id_user);


--
-- TOC entry 4992 (class 2606 OID 16912)
-- Name: pengumpulan_tugas pengumpulan_tugas_id_tugas_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pengumpulan_tugas
    ADD CONSTRAINT pengumpulan_tugas_id_tugas_fkey FOREIGN KEY (id_tugas) REFERENCES public.tugas(id_tugas);


--
-- TOC entry 4997 (class 2606 OID 16985)
-- Name: pengumuman pengumuman_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pengumuman
    ADD CONSTRAINT pengumuman_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.users(id_user);


--
-- TOC entry 4995 (class 2606 OID 16956)
-- Name: sertifikat sertifikat_id_siswa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sertifikat
    ADD CONSTRAINT sertifikat_id_siswa_fkey FOREIGN KEY (id_siswa) REFERENCES public.users(id_user);


--
-- TOC entry 4996 (class 2606 OID 16961)
-- Name: sertifikat sertifikat_id_tentor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sertifikat
    ADD CONSTRAINT sertifikat_id_tentor_fkey FOREIGN KEY (id_tentor) REFERENCES public.users(id_user);


--
-- TOC entry 4978 (class 2606 OID 16626)
-- Name: siswa_kelas siswa_kelas_id_kelas_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.siswa_kelas
    ADD CONSTRAINT siswa_kelas_id_kelas_fkey FOREIGN KEY (id_kelas) REFERENCES public.kelas(id_kelas);


--
-- TOC entry 4979 (class 2606 OID 16621)
-- Name: siswa_kelas siswa_kelas_id_siswa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.siswa_kelas
    ADD CONSTRAINT siswa_kelas_id_siswa_fkey FOREIGN KEY (id_siswa) REFERENCES public.users(id_user);


--
-- TOC entry 4980 (class 2606 OID 16654)
-- Name: siswa_kursus siswa_kursus_id_kursus_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.siswa_kursus
    ADD CONSTRAINT siswa_kursus_id_kursus_fkey FOREIGN KEY (id_kursus) REFERENCES public.kursus(id_kursus);


--
-- TOC entry 4981 (class 2606 OID 16649)
-- Name: siswa_kursus siswa_kursus_id_siswa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.siswa_kursus
    ADD CONSTRAINT siswa_kursus_id_siswa_fkey FOREIGN KEY (id_siswa) REFERENCES public.users(id_user);


--
-- TOC entry 4982 (class 2606 OID 16687)
-- Name: tentor_kursus tentor_kursus_id_kursus_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tentor_kursus
    ADD CONSTRAINT tentor_kursus_id_kursus_fkey FOREIGN KEY (id_kursus) REFERENCES public.kursus(id_kursus);


--
-- TOC entry 4983 (class 2606 OID 16682)
-- Name: tentor_kursus tentor_kursus_id_tentor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tentor_kursus
    ADD CONSTRAINT tentor_kursus_id_tentor_fkey FOREIGN KEY (id_tentor) REFERENCES public.users(id_user);


--
-- TOC entry 4989 (class 2606 OID 16823)
-- Name: tugas tugas_id_kelas_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tugas
    ADD CONSTRAINT tugas_id_kelas_fkey FOREIGN KEY (id_kelas) REFERENCES public.kelas(id_kelas);


--
-- TOC entry 4990 (class 2606 OID 16828)
-- Name: tugas tugas_id_tentor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tugas
    ADD CONSTRAINT tugas_id_tentor_fkey FOREIGN KEY (id_tentor) REFERENCES public.users(id_user);


--
-- TOC entry 5165 (class 0 OID 0)
-- Dependencies: 226
-- Name: TABLE absensi_siswa; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.absensi_siswa TO admin;
GRANT SELECT,INSERT,UPDATE ON TABLE public.absensi_siswa TO operator;
GRANT SELECT,INSERT,UPDATE ON TABLE public.absensi_siswa TO tentor;
GRANT SELECT,INSERT ON TABLE public.absensi_siswa TO siswa;


--
-- TOC entry 5166 (class 0 OID 0)
-- Dependencies: 227
-- Name: TABLE absensi_tentor; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.absensi_tentor TO admin;
GRANT SELECT,INSERT,UPDATE ON TABLE public.absensi_tentor TO operator;


--
-- TOC entry 5167 (class 0 OID 0)
-- Dependencies: 225
-- Name: TABLE jadwal; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.jadwal TO admin;
GRANT SELECT,INSERT,UPDATE ON TABLE public.jadwal TO operator;
GRANT SELECT ON TABLE public.jadwal TO tentor;
GRANT SELECT ON TABLE public.jadwal TO siswa;


--
-- TOC entry 5168 (class 0 OID 0)
-- Dependencies: 221
-- Name: TABLE kelas; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.kelas TO admin;
GRANT SELECT,INSERT,UPDATE ON TABLE public.kelas TO operator;
GRANT SELECT ON TABLE public.kelas TO tentor;
GRANT SELECT ON TABLE public.kelas TO siswa;


--
-- TOC entry 5169 (class 0 OID 0)
-- Dependencies: 220
-- Name: TABLE kursus; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.kursus TO admin;
GRANT SELECT,INSERT,UPDATE ON TABLE public.kursus TO operator;
GRANT SELECT ON TABLE public.kursus TO tentor;
GRANT SELECT ON TABLE public.kursus TO siswa;


--
-- TOC entry 5170 (class 0 OID 0)
-- Dependencies: 230
-- Name: TABLE nilai; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.nilai TO admin;
GRANT SELECT ON TABLE public.nilai TO operator;
GRANT SELECT,INSERT,UPDATE ON TABLE public.nilai TO tentor;
GRANT SELECT ON TABLE public.nilai TO siswa;


--
-- TOC entry 5171 (class 0 OID 0)
-- Dependencies: 229
-- Name: TABLE pengumpulan_tugas; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.pengumpulan_tugas TO admin;
GRANT SELECT ON TABLE public.pengumpulan_tugas TO operator;
GRANT SELECT ON TABLE public.pengumpulan_tugas TO tentor;
GRANT SELECT,INSERT,UPDATE ON TABLE public.pengumpulan_tugas TO siswa;


--
-- TOC entry 5172 (class 0 OID 0)
-- Dependencies: 232
-- Name: TABLE pengumuman; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.pengumuman TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.pengumuman TO operator;
GRANT SELECT ON TABLE public.pengumuman TO siswa;


--
-- TOC entry 5173 (class 0 OID 0)
-- Dependencies: 231
-- Name: TABLE sertifikat; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sertifikat TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.sertifikat TO tentor;
GRANT SELECT ON TABLE public.sertifikat TO siswa;


--
-- TOC entry 5174 (class 0 OID 0)
-- Dependencies: 222
-- Name: TABLE siswa_kelas; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.siswa_kelas TO admin;
GRANT SELECT,INSERT,UPDATE ON TABLE public.siswa_kelas TO operator;
GRANT SELECT ON TABLE public.siswa_kelas TO tentor;
GRANT SELECT ON TABLE public.siswa_kelas TO siswa;


--
-- TOC entry 5175 (class 0 OID 0)
-- Dependencies: 223
-- Name: TABLE siswa_kursus; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.siswa_kursus TO admin;
GRANT SELECT,INSERT,UPDATE ON TABLE public.siswa_kursus TO operator;
GRANT SELECT ON TABLE public.siswa_kursus TO tentor;
GRANT SELECT ON TABLE public.siswa_kursus TO siswa;


--
-- TOC entry 5176 (class 0 OID 0)
-- Dependencies: 224
-- Name: TABLE tentor_kursus; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tentor_kursus TO admin;
GRANT SELECT,INSERT,UPDATE ON TABLE public.tentor_kursus TO operator;
GRANT SELECT ON TABLE public.tentor_kursus TO tentor;
GRANT SELECT ON TABLE public.tentor_kursus TO siswa;


--
-- TOC entry 5177 (class 0 OID 0)
-- Dependencies: 228
-- Name: TABLE tugas; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tugas TO admin;
GRANT SELECT ON TABLE public.tugas TO operator;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.tugas TO tentor;
GRANT SELECT ON TABLE public.tugas TO siswa;


--
-- TOC entry 5178 (class 0 OID 0)
-- Dependencies: 219
-- Name: TABLE users; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.users TO admin;
GRANT SELECT,INSERT,UPDATE ON TABLE public.users TO operator;
GRANT SELECT ON TABLE public.users TO siswa;


-- Completed on 2026-10-11 19:58:43

--
-- PostgreSQL database dump complete
--

\unrestrict aoIUtOlGeeMd2AOjQcuQEUOOnQA3jotXht02gjgGMfEVln76eApxsorYKJzt3ov

