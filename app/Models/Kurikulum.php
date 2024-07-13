<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    use HasFactory;
    protected $table = 'kurikulums';
    protected $primaryKey = 'tahun';
    public $incrementing = false;
    protected $fillable = [
        'tahun'
    ];
}
