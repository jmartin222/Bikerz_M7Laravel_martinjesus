<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrocini extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_cursa',
        'cif_sponsor'
    ];
}
