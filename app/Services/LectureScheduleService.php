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
            return 'The reservation does not exist.';

        $conference = $reservation->conference()->first();
        if (!$conference)
            return 'Unable to save schedule.';

        $lectures = $conference->lectures()->get();
        $scheduledLectureIds = array_keys($scheduled);
        $scheduledLectures = $lectures->whereIn('id', $scheduledLectureIds);

        foreach ($scheduledLectures as $lecture1)
            foreach ($scheduledLectures as $lecture2)
                if ($lecture1->id !== $lecture2->id &&
                    $lecture1->start_time < $lecture2->end_time && 
                    $lecture1->end_time > $lecture2->start_time)
                        return 'Time collision detected between '.$lecture1->title .' and '.$lecture2->title.'.';


        foreach ($lectures as $lecture) {
            LectureSchedule::where('lecture_id', $lecture->id)
                ->where('reservation_id', $reservationId)
                ->delete();
            
            if (in_array($lecture->id, array_keys($scheduled))) {
                LectureSchedule::create([
                    'lecture_id' => $lecture->id,
                    'reservation_id' => $reservationId,
                ]);
            }
        }

        return null;
    }
}
