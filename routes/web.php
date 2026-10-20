<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\KrsController;

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
Route::get('/mata-kuliah', [MatakuliahController::class, 'index'])->name('matakuliah.index');
Route::get('/krs', [KrsController::class, 'index'])->name('krs.index');

Route::view('/uji-table', 'uji-table');