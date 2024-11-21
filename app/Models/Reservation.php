<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservation';

    protected $fillable = [
        'user_id',
        'conference_id',
        'is_confirmed',
        'number_of_people',
        'is_paid',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class, 'conference_id');
    }

    public function schedules()
    {
        return $this->hasMany(LectureSchedule::class, 'reservation_id');
    }
}
