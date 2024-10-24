<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'room';

    protected $fillable = [
        'conference_id',
        'name'
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class, 'conference_id');
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'room_id');
    }
}
