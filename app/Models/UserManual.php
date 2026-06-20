<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserManual extends Model
{
    protected $table = 'user_manuals';

    protected $fillable = [
        'title',
        'url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
