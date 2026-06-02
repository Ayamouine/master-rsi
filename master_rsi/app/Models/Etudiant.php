<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $table = 'etudiants';

    protected $primaryKey = 'id';

    protected $fillable = [
        'login',
        'pass',
        'nom',
        'filiere',
        'bio',
        'phone',
        'email',
        'city',
        'linkedin',
        'github',
        'note1',
        'note2',
        'quiz1',
        'quiz2',
        'moyenne',
        'longitude',
        'latitude',
        'profile_skills',
        'profile_interests',
        'profile_experience',
    ];

    protected $casts = [
        'profile_skills' => 'array',
        'profile_interests' => 'array',
        'profile_experience' => 'array',
    ];

    // si tu ne veux pas timestamps
    // public $timestamps = false;
}
