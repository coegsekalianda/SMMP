<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $table = 'activities';

    protected $fillable = [
        'minggu', 'sub_cpmk', 'indikator', 'kriteria', 'metode_luring', 'metode_daring', 'materi', 'bobot', 'id_rps'
    ];

    public function rps()
    {
        return $this->belongsTo(RPS::class, 'id_rps');
    }
}
