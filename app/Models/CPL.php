<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CPL extends Model
{
    use HasFactory;
    protected $table = 'cpls';

    protected $fillable = [
        'aspek', 'kode', 'nomor', 'judul', 'kurikulum'
    ];

    public function mutus()
    {
        return $this->hasMany(Mutu::class);
    }
}
