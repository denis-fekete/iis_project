<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    use HasFactory;

    protected $table = 'conference';

    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'theme',
        'start_time',
        'end_time',
        'place_address',
        'price',
        'capacity'
    ];

    // tells laravel to cast time into Carbon instead of string (easier to manipulate)
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'conference_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'conference_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'conference_id');
    }
}
