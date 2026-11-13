<?php

// Nama   : Bima Artayasa
// Nim    : 3202416151
// Kelas  : TIF 5 C

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KrsDetail extends Model
{
    //
    protected $table = 'krs_detail';

    protected $fillable = ['krs_ud', 'mata_kulaih_id'];

    public function krs(): BelongsTo
    {
        return $this->belognsTo(Krs::class, 'krs_id');
    }
    public function mataKulaih(): BelongsTo
    {
        return $this->belongsTo(MataKulaih::class, 'mata_kulaih_id');
    }
}


