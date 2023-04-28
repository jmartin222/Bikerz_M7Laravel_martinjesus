<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'CIF',
        'nom',
        'logo',
        'adreca',
        'curses',
        'primera_plana',
        'actiu'
    ];

    // protected $casts = [
    //     'curses' => 'array'
    // ];
}