<?php

namespace App\Services;


use App\Models\Room;

class RoomService
{
    /**
     * @param  string $id id of the room
     * @return Room|null returns model of Room found
     */
    public static function getRoomById($id) {
        return Room::find($id);
    }
}
