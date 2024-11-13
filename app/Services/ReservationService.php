<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Conference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ReservationService
{
    /**
     * Creates new reservation
     *
     * @param  Request $request HTTP POST containing all information
     * @param  string $userId User ID
     * @return string On error returns error message, on success returns empty string
     */
    public static function create(Request $request, $userId) : string {
        $conferenceId = $request->input('conferenceId');
        $people = $request->input('number_of_people');

        $capacityLeft = ConferenceService::capacityLeft($conferenceId);
        if($capacityLeft < 0) {
            return "Conference was not found";
        }

        if($capacityLeft < $people) {
            return 'Not enough seats are available';
        }

        $res = Reservation::create([
            'conference_id' => $conferenceId,
            'user_id' => $userId,
            'number_of_people' => $people,
        ]);

        if($res == null) {
            return 'Internal error: Creation of Reservation failed';
        }

        return '';
    }

    /**
     * Returns all Reservations in database
     *
     * @return Collection of Reservations
     */
    public static function getAll() : Collection {
        return Reservation::all();
    }


    /**
     * Returns all Reservations in database
     * @param  string $id ID of user whose Reservations will be returned
     * @return Collection of Reservations
     */
    public static function getAllMyWithConferenceInfo($id) : Collection {
        return Reservation::where('user_id', $id)
            ->with('conference:id,title,start_time,end_time')
            ->get();
    }

    /**
     * Returns reservation by ID
     *
     * @param  sting $id ID of the Reservation
     * @return Reservation that was found or null
     */
    public static function get($id) : Reservation {
        return Reservation::find($id);
    }

    public static function cancel($userId, $reservationId) : string {
        $reservation = Reservation::find($reservationId);

        if($reservation->user_id != $userId) {
            return 'Error: You do not have permission to delete Reservation that is not yours';
        }

        $reservation->delete();

        return '';
    }
}
