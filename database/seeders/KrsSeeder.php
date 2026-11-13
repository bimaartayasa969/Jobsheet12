<?php

// Nama     : Bima Artayasa
// Nim      : 3202416151
// Kelas    : TIF 5 C

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class KrsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $mahasiswa = Mahasiswa::orderBy('id')->limit(10)->get();

        foreach ($mahasiswa as $mhs) {
            $krs = Krs::create([
                'mahasiswa_id' => $mhs->id,
                'tahun_akademik' => '2026/2027',
                'semester' => 'Ganjil',
            ]);

            $ids = MataKuliah::inRandomOrder()->limit(05)->pluck('id');
            $krs->mataKuliah()->attach($ids);
        }
    }
}
