<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RPS extends Model
{
    use HasFactory;
    protected $table = 'rpss';

    protected $fillable = [
        'kode_mk','nomor','prodi', 'dosen','pengembang','koordinator','kaprodi','kurikulum', 'semester', 'materi_mk', 'pustaka_utama', 'pustaka_pendukung', 'tipe', 'waktu', 'syarat_ujian', 'syarat_studi','media','kontrak'
    ];

    public function mk()
    {
        return $this->belongsTo(MK::class, 'kode_mk','kode');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'id_rps');
    }


}
