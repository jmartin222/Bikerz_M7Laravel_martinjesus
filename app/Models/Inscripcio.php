<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcio extends Model
{
    use HasFactory;

    protected $table = 'inscripcions';

    protected $fillable = [
        'id_cursa',
        'dni_participant',
        'temps'
    ];
}
