<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CPMK extends Model
{
    use HasFactory;
    protected $table = 'cpmks';
    public $timestamps = false;

    protected $fillable = [
        'kode_mk', 'judul'
    ];

    public function mk()
    {
        return $this->belongsTo(MK::class, 'kode_mk');
    }

    public function soal()
    {
        return $this->belongsToMany(Soal::class, 'cpmk_soals', 'id_cpmk', 'id_soal')->withTimestamps();
    }

    public function mutus()
    {
        return $this->hasMany(Mutu::class);
    }
}
