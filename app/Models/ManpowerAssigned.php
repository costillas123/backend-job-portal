<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManpowerAssigned extends Model
{
    use HasFactory;

    protected $table = 'manpower_assigned';

    protected $fillable = [
        'manpower_user_id',
        'employer_user_id',
    ];

    public function manpower()
    {
        return $this->belongsTo(User::class, 'manpower_user_id');
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_user_id');
    }
}
