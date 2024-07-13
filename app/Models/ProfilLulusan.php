<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilLulusan extends Model
{
    use HasFactory;
    protected $table = 'profil_lulusan';
    public $timestamps = false;
    protected $fillable = ['namaProfil','deskripsi'];
    
    protected $guarded = ['id'];
}
