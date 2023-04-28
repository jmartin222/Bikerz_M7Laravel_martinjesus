<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cursa extends Model
{
    use HasFactory;

    protected $table = 'curses';

    protected $fillable = [
        'descripcio',
        'desnivell',
        'img_mapa',
        'max_participants',
        'longitud',
        'data_cursa',
        'hora_cursa',
        'punt_sortida',
        'cartell_promocio',
        'cost_patrocini',
        'actiu'
    ];

    // protected $casts = [
    //     'data_cursa' => 'datetime',
    // ];
}
