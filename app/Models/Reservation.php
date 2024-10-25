<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservation';

    protected $fillable = [
        'user_id',
        'conference_id',
        'is_confirmed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class, 'conference_id');
    }
}
