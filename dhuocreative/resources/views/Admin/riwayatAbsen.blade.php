@extends('layouts.Admin')

@section('title', 'Absensi Siswa')

@section('content')
    <main class="ra-page">
        <header class="ra-page-head">
            <div>
                <h1 class="ra-page-title">Riwayat Absensi</h1>
            </div>
 
            <select class="ra-select" aria-label="Pilih kelas">
                <option>Web Development</option>
                <option>Desain Grafis</option>
            </select>
        </header>
 
        <div class="ra-table-wrap">
            <table class="ra-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="ra-left">Nama Siswa</th>
                        <th>Hadir</th>
                        <th>Persentase</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ra-no">01</td>
                        <td class="ra-left"><strong>Aditya Pratama</strong></td>
                        <td>3/5</td>
                        <td class="ra-percent ra-percent-low">60%</td>
                        <td><a href="{{ route('admin.riwayatPresensi') }}" class="ra-btn">Lihat detail</a></td>
                    </tr>
                    <tr>
                        <td class="ra-no">02</td>
                        <td class="ra-left"><strong>Cahyo Nugroho</strong></td>
                        <td>4/5</td>
                        <td class="ra-percent">80%</td>
                        <td><a href="{{ route('admin.riwayatPresensi') }}" class="ra-btn">Lihat detail</a></td>
                    </tr>
                    <tr>
                        <td class="ra-no">03</td>
                        <td class="ra-left"><strong>Dewi Anggraini</strong></td>
                        <td>3/5</td>
                        <td class="ra-percent ra-percent-low">60%</td>
                        <td><a href="{{ route('admin.riwayatPresensi') }}" class="ra-btn">Lihat detail</a></td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </main>   
@endsection