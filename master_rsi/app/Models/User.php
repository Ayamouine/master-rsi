<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'etudiants'; // 👈 important

    protected $primaryKey = 'id';

    protected $fillable = [
        'login',
        'pass',
        'nom',
        'note1',
        'note2',
        'moyenne',
        'longitude',
        'latitude',
    ];

    protected $hidden = [
        'pass',
    ];
}
