<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corredor extends Model
{
    use HasFactory;

    protected $fillable = [
        'DNI',
        'nom',
        'adreca',
        'data_naixement',
        'sexe',
        'punts'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
