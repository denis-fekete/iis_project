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
     * @param  mixed $userId Owner of reservation
     * @param  mixed $conferenceId Conference to which user is registering
     * @param  mixed $people Number of people this reservation is for
     * @param  mixed $capacityLeft How much free space is in available
     * @return string|null Returns error message if error occurred or null on success
     */

    /**
     * create
     *
     * @param Request  $request
     * @return string
     */
    public function create(Request $request, $userId) : string {
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
}
