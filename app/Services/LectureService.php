<?php

namespace App\Services;

use App\Models\Conference;
use App\Models\Lecture;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class LectureService
{
    public static function getLecturesAssignedToUser($id) {
        $usersLectures = Lecture::where('speaker_id', '=', $id)->get();
        $dashBoardLectureViewModels = [];
        foreach ($usersLectures as $lecture) {
            $conference = Conference::find($lecture->conference_id);
            array_push($dashBoardLectureViewModels, [
                'id' => $lecture->id,
                'title' => $lecture->title,
                'conferenceId' => $conference->id,
                'conferenceTitle' => $conference->title,
                'isConfirmed' => $lecture->is_confirmed,
            ]);
        }

        return $dashBoardLectureViewModels;
    }

    public static function getLectureById($id) {
        return Lecture::find($id);
    }

    public static function updateLectureInfo($id, $data) {
        $lecture = Lecture::find($id);

        $lecture->title = $data->input('title');
        $lecture->poster = $data->input('poster');
        $lecture->description = $data->input('description');
        $lecture->save();
    }

    public static function createLecture($userId, $data) {
        Lecture::create([
            'conference_id' => $data->input('conference_id'),
            'speaker_id' => $userId,
            'title' => $data->input('title'),
            'description' => $data->input('description'),
            'poster' => $data->input('poster') ?? '',
            'is_confirmed' => false,
        ]);
    }

    public static function getLectureDetailView($id, $userId) {
        $lecture = Lecture::find($id);
        $owner = User::find($lecture->speaker_id);
        $conference = Conference::find($lecture->conference_id);
        $room = Room::find($lecture->room_id);

        $canEdit = LectureService::checkEditPolicy($id, $userId);

        $data = [
            'id' => $lecture->id,
            'title' => $lecture->title,
            'description' => $lecture->description,
            'posterUrl' => $lecture->poster ?? null,
            'startTime' => $lecture->start_time ?? null,
            'endTime' => $lecture->end_time ?? null,
            'ownerId' => $owner->id,
            'ownerName' => $owner->name.' '.$owner->surname,
            'room' => $room ? $room->name : null,
            'conferenceName' => $conference->title,
            'conferenceId' => $conference->id,
            'isConfirmed' => $lecture->is_confirmed,
            'canEdit' => $canEdit === null ? false : true,
        ];

        return $data;
    }

    public static function cancelLecture($speakerId, $lectureId) {
        $lecture = Lecture::find($lectureId);
        if (!$lecture)
            return 'lecture-does-not-exist';

        if ($lecture->is_confirmed)
            return 'confirmed-lecture-cannot-be-cancelled';

        $lecture->delete();
        return null;
    }

    public static function confirm($data) {
        $lecture = Lecture::find($data->input('id'));
        if (!$lecture)
            return 'Lecture does not exist';

        if ($lecture->is_confirmed)
            return 'Lecture is already confirmed';

        $conference = Conference::find($lecture->conference_id);
        $room = Room::find($data->input('roomId'));
        $startTime = Carbon::parse($data->input('startTime'));
        $endTime = Carbon::parse($data->input('endTime'));

        if ($startTime < $conference->start_time || $endTime > $conference->end_time)
            return 'Time beyond conference';

        $collidingLectures = $conference->lectures()
            ->where('is_confirmed', true)
            ->where('id', '!=', $lecture->id)
            ->where('room_id', $room->id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime]);
            })->exists();
        
        if ($collidingLectures)
            return 'Collision with another lecture';

        $lecture->room_id = $room->id;
        $lecture->start_time = $startTime;
        $lecture->end_time = $endTime;
        $lecture->is_confirmed = true;
        $lecture->update();

        return null;
    }

    public static function unconfirm($lectureId) {
        $lecture = Lecture::find($lectureId);
        if (!$lecture->is_confirmed)
            return 'Lecture was not confirmed';

        $lecture->is_confirmed = false;
        $lecture->room_id = null;
        $lecture->start_time = null;
        $lecture->end_time = null;
        $lecture->update();

        return null;
    }

    public static function checkEditPolicy($lectureId, $userId) {
        $lecture = Lecture::find($lectureId);
        if (!$lecture)
            return 'lecture-does-not-exist';
        if ($lecture->speaker_id != $userId)
            return 'restricted';
        return null;
    }

    public static function checkSchedulePolicy($lectureId, $userId) {
        $lecture = Lecture::find($lectureId);
        if (!$lecture)
            return 'Cannot find lecture';
        $conference = Conference::find($lecture->conference_id);
        if (!$conference)
            return 'Server error';
        if ($conference->owner_id != $userId)
            return 'Restricted';
        return null;
    }
}
