<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    //
    protected $fillable = [
        'bio',
        'industry',
        'user_id',
        'profile_picture'
    ];

}
