<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilCpl extends Model
{
    use HasFactory;
    protected $table = 'profil_cpl';
    public $timestamps = false;
    protected $fillable = ['idProfil', 'idCpl','bobot'];

    protected $guarded = ['id'];
}
