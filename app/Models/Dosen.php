<?php

// Nama    : Bima Artayasa
// Nim     : 3202416151
// Kelas   : TIF 5 C

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    /** @use HasFactory<\Database\Factories\DosenFactory> */
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'nidn',
        'nama',
        'email',
        'telepon',
        'alamat',
        'jabatan_fungsional'
    ];
}
