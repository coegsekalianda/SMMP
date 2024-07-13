<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutu extends Model
{
    use HasFactory;
    protected $table = 'mutus';
    public $timestamps = false;
    protected $fillable = ['universitas','tahun', 'NPM','angkatan', 'Nama', 'prodi', 'Course','namaCourse','Jenis','examWeight','soal', 'idSoal', 'BobotSoal','Cpl','Cpmk','Nilai','nilaiSoal'];

    protected $guarded = ['id'];


    public function cpl()
    {
        return $this->belongsTo(Cpl::class);
    }

    public function cpmk()
    {
        return $this->belongsTo(Cpmk::class);
    }
}
