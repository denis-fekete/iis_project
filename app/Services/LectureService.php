<?php

namespace App\Services;

use App\Models\Conference;
use App\Models\Lecture;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class LectureService
{
    public static function getLecturesAssignedToUser($id) {
        return Lecture::where('id', '=', $id)->get();
    }

    public static function getLectureById($id) {
        return Lecture::find($id);
    }

    public static function updateLectureInfo($id, $data) {
        $lecture = Lecture::find($id);

        $lecture->title = $data->input('title');
        $lecture->poster = $data->input('poster');
        $lecture->start_time = $data->input('start_time');
        $lecture->end_time = $data->input('end_time');
        $lecture->save();
    }

    public static function createLecture($userId, $data) {
        Lecture::create([
            'conference_id' => $data->input('conference_id'),
            'speaker_id' => $userId,
            'title' => $data->input('title'),
            'poster' => $data->input('poster') ?? '',
            'start_time' => $data->input('start_time'),
            'end_time' => $data->input('end_time'),
        ]);
    }

    public static function getLectureDetailView($id) {
        $lecture = Lecture::find($id);
        $owner = User::find($lecture->speaker_id);
        $conference = Conference::find($lecture->conference_id);
        $room = Room::find($lecture->room_id);

        $data = [
            'title' => $lecture->title,
            'posterUrl' => $lecture->poster ?? null,
            'startTime' => $lecture->start_time,
            'endTime' => $lecture->end_time,
            'ownerId' => $owner->id,
            'ownerName' => $owner->name.' '.$owner->surname,
            'room' => $room ? $room->name : null,
            'conferenceName' => $conference->title,
            'conferenceId' => $conference->id,
            'isConfirmed' => $lecture->is_confirmed,
        ];

        return $data;
    }
}
