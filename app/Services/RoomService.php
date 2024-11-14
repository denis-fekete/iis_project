<?php

namespace App\Services;


use App\Models\Room;

class RoomService
{
    public static function getRoomById($id) {
        return Room::find($id);
    }
}
