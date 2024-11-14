<?php

namespace App\Services;


use App\Models\Lecture;
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
}
