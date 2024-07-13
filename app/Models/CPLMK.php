<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CPLMK extends Model
{
    use HasFactory;
    protected $table = 'cplmks';

    protected $fillable = [
        'kode_mk', 'id_cpl'
    ];

    public function mk()
    {
        return $this->belongsTo(MK::class, 'kode_mk');
    }

    public function cpl()
    {
        return $this->belongsTo(CPL::class, 'id_cpl');
    }
}
