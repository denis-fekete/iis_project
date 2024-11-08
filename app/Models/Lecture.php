<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    protected $table = 'lecture';

    protected $fillable = [
        'conference_id',
        'speaker_id',
        'roomId',
        'title',
        'poster',
        'is_confirmed',
        'start_time',
        'end_time'
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class, 'conference_id');
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'speaker_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function schedules()
    {
        return $this->hasMany(LectureSchedule::class, 'lecture_id');
    }
}
