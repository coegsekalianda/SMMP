<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;
    protected $table = 'soals';

    protected $fillable = [
        'kode_mk', 'minggu', 'jenis', 'dosen', 'kurikulum', 'pertanyaan', 'status', 'komentar', 'bobotSoal', 'cpl', 'cpmk', 'kodeSoal'
    ];

    public function cpmk()
    {
        return $this->belongsToMany(CPMK::class,'cpmk_soals','id_soal','id_cpmk')->withTimestamps();
    }

    public function mk()
    {
        return $this->belongsTo(MK::class, 'kode_mk', 'kode');
    }
}
