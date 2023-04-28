<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asseguradora extends Model
{
    use HasFactory;

    protected $table = 'asseguradores';

    protected $fillable = [
        'CIF',
        'nom',
        'adreca',
        'preu_per_cursa',
        'actiu'
    ];
}
