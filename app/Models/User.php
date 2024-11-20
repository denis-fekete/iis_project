<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'user';

    protected $fillable = [
        'name',
        'surname',
        'title_before',
        'title_after',
        'description',
        'email',
        'password',
        'role'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    public function conferences()
    {
        return $this->hasMany(Conference::class, 'conference_id');
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'speaker_id');
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
