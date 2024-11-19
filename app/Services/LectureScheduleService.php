<?php

namespace App\Services;

use App\Models\LectureSchedule;
use App\Models\Reservation;

class LectureScheduleService
{
    private static function checkUserPolicy($reservationId) {
        $userId = auth()->user()->id;
        $reservation = Reservation::find($reservationId);
        if (!$reservation || $reservation->user_id != $userId)
            return false;
        return true;
    } 

    public static function getLectureSchedule($reservationId) {
        if (!LectureScheduleService::checkUserPolicy($reservationId))
            return null;
    
        $reservation = Reservation::find($reservationId);
        if (!$reservation)
            return null;
    
        $conference = $reservation->conference()->first();
        if (!$conference)
            return null;
    
        $lectures = $conference->lectures()
            ->where('is_confirmed', 1)
            ->orderBy('start_time')
            ->with(['room'])
            ->get();
    
        $lecturesViewModels = $lectures->map(function ($lecture) use ($reservationId) {
            $scheduled = LectureSchedule::query()
                ->where('lecture_id', $lecture->id)
                ->where('reservation_id', $reservationId)
                ->exists();
    
            return [
                'id' => $lecture->id,
                'title' => $lecture->title,
                'startTime' => $lecture->start_time,
                'endTime' => $lecture->end_time,
                'room' => $lecture->room->name,
                'scheduled' => $scheduled,
            ];
        });
    
        return $lecturesViewModels;
    }

    public static function saveLectureSchedule($reservationId, $scheduled) {
        $reservation = Reservation::find($reservationId);
        if (!$reservation)
            return false;

        $conference = $reservation->conference()->first();
        if (!$conference)
            return false;

        $lectures = $conference->lectures()->pluck('id')->toArray();
        foreach ($lectures as $lectureId) {
            LectureSchedule::where('lecture_id', $lectureId)
                ->where('reservation_id', $reservationId)
                ->delete();
            
            if (in_array($lectureId, array_keys($scheduled))) {
                LectureSchedule::create([
                    'lecture_id' => $lectureId,
                    'reservation_id' => $reservationId,
                ]);
            }
        }

        return true;
    }
}
