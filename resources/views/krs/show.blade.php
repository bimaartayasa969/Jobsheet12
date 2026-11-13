@extends('layouts.app')

@section('title', 'Detail KRS')

@section('content')
<x-page-header judul="Detail KRS" deskripsi="Informasi KRS beserta mata kuliah yang diambil." />

<div class="card border-0 shadow-sm">
    <div class="card-header bg-body fw-semibold">KRS {{ $krs->mahasiswa->nama }}</div>
    <div class="card-body">
        <dl class="row mb-3">
            <dt class="col-sm-3">NIM</dt>
            <dd class="col-sm-9">{{ $krs->mahasiswa->nim }}</dd>

            <dt class="col-sm-3">Nama</dt>
            <dd class="col-sm-9">{{ $krs->mahasiswa->nama }}</dd>

            <dt class="col-sm-3">Tahun Akademik</dt>
            <dd class="col-sm-9">{{ $krs->tahun_akademik }}</dd>

            <dt class="col-sm-3">Semester</dt>
            <dd class="col-sm-9">{{ $krs->semester }}</dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9">
                <span @class([
                    'badge',
                    'bg-secondary' => $krs->status === 'draft',
                    'bg-info text-dark' => $krs->status === 'diajukan',
                    'bg-success' => $krs->status === 'disetujui',
                    'bg-danger' => $krs->status === 'ditolak',
                ])>{{ ucfirst($krs->status) }}</span>
            </dd>
        </dl>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode MK</th>
                    <th>Nama Mata Kuliah</th>
                    <th>SKS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($krs->mataKuliah as $mk)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $mk->kode_mk }}</td>
                    <td>{{ $mk->nama_mk }}</td>
                    <td>{{ $mk->sks }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">Belum ada mata kuliah.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total SKS</th>
                    <th>{{ $krs->mataKuliah->sum('sks') }}</th>
                </tr>
            </tfoot>
        </table>

        <a href="{{ route('krs.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        @if (Auth::user()->role === 'admin')
        <a href="{{ route('krs.edit', $krs) }}" class="btn btn-warning">
            <i class="bi bi-pencil-square"></i> Ubah
        </a>
        @endif
    </div>
</div>
@endsection