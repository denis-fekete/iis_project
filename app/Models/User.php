<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'role'
    ];

    public function schedules()
    {
        return $this->hasMany(LectureSchedule::class, 'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    // Means password will not be included while serialization to JSON
    protected $hidden = [
        'password',
    ];


    // Means password will be stared as hash
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
