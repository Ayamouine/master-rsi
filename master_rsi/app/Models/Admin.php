<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table    = 'admins';
    protected $fillable = ['login', 'pass', 'nom', 'bio', 'phone', 'email', 'city', 'linkedin', 'github', 'profile_skills', 'profile_interests', 'profile_experience'];

    protected $casts = [
        'profile_skills' => 'array',
        'profile_interests' => 'array',
        'profile_experience' => 'array',
    ];
}
