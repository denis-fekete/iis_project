<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureSchedule extends Model
{
    protected $table = 'lecture_schedule';

    protected $fillable = [
        'user_id',
        'lecture_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class, 'lecture_id');
    }
}
