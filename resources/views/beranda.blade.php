@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h1 class="h4 fw-bold">{{ $namaAplikasi }}</h1>
        <p class="text-body-secondary">Konfigurasi berhasil. Hari ini {{ $waktu }}.</p>

        <table class="table table-bordered w-auto">
            <tr><th>Laravel</th><td>{{ $versiLaravel }}</td></tr>
            <tr><th>PHP</th><td>{{ $versiPhp }}</td></tr>
            <tr><th>MySQL</th><td>{{ $versiMysql }}</td></tr>
            <tr><th>Database</th><td>{{ $namaDatabase }}</td></tr>
        </table>
    </div>
</div>
@endsection