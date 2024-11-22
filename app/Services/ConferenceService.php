<?php

namespace App\Services;

use App\Enums\OrderBy;
use App\Enums\Themes;
use App\Enums\OrderDirection;
use App\Models\Conference;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ConferenceService
{
    const MAX_DESCRIPTION_LEN = 250;

    private const VALIDATOR_PARAMS = [
        'title' => 'required|min:3|max:100',
        'description' => 'required|min:20|max:10000',
        'theme' => 'required|min:2|max:100',
        'start_time' => 'required|date',
        'end_time' => 'required|date',
        'place_address' => 'required',
        'price' => 'required',
        'capacity' => 'required',
        'poster' => 'required|url',
        'bank_account' => 'required',
    ];

    /**
     * Returns all conferences in database
     *
     * @param  Themes|null $themes themes that will be displayed
     * @param  OrderBy|null $orderBy order by a parameter
     * @param  OrderDirection|null $orderDir ascending or descending
     * @param  string|null $searchString string that will be searched in `title` of conferences
     * @return Collection a collection of all conferences that meet the criteria
     */
    public static function getAll($themes = null, $orderBy = null, $orderDir = null, $searchString = null): Collection {
        $query = Conference::query();

        if($searchString !== null) {
            $query->where('title', 'LIKE', '%' . $searchString . '%');
        }


        if($themes !== null) {
            $query->where('theme', $themes);
        }

        switch($orderDir) {
            case OrderDirection::Descending->value:
            case OrderDirection::Ascending->value:
                break;
            default:
                $orderDir = 'asc';
                break;
        }

        switch($orderBy) {
            case OrderBy::Name->value :
                $query->orderBy('title', $orderDir);
                break;
            case OrderBy::Price->value :
                $query->orderBy('price', $orderDir);
                break;
            case OrderBy::Newest->value :
            case OrderBy::Oldest->value :
                $query->orderBy('start_time', $orderDir);
                break;
            default:
                break;
        }


        $result = $query->get();
        if($orderDir == OrderBy::Oldest->value) {
            $result = $result->reverse();
        }

        return $result;
    }

    /**
     * Returns all conferences in database but the description length is capped
     *
     * @return Collection of Conferences with capped length of description
     */
    public static function getAllShortDescription($themes = null, $orderBy = null, $orderDir = null, $searchString = null): Collection {
        $conferences = self::getAll($themes, $orderBy, $orderDir, $searchString);

        foreach($conferences as $key => $val) {
            $conferences[$key]->description = substr($conferences[$key]->description, 0, self::MAX_DESCRIPTION_LEN);
        }

        return $conferences;
    }

    /**
     * Returns a conference by id
     *
     * @param  mixed $id ID of the conference
     * @return Conference Conference or null if not found
     */
    public static function get($id) {
        return Conference::find($id);
    }

    /**
     * Returns a conference by id
     *
     * @param  mixed $id ID of the conference
     * @return Conference Conference or null if not found
     */
    public static function getOwner($id) {
        return Conference::find($id)->owner_id;
    }

    /**
     * Returns a conference by id and puts owner id and name into it
     *
     * @param  mixed $id ID of the conference
     * @return Conference Conference or null if not found
     */
    public static function getWithLectures($id) {
        // add owner and lectures info now for less database requests
        return Conference::with('owner:id,name,surname')
            ->with('lectures.lecturer:id,name,surname')
            ->find($id);
    }

    /**
     * Returns a conference by id and puts owner id and name into it
     *
     * @param  mixed $id ID of the conference
     * @return mixed ViewModel with info about conference reservations and statistics
     */
    public static function getReservations($id) {
        $conference = Conference::find($id);
        $reservationsQuery = $conference->reservations();

        $reservations = $reservationsQuery->get();
        $reservationCount = 0;
        foreach($reservations as $res)
            $reservationCount += $res->number_of_people;

        $confirmedReservations = $reservationsQuery->where('is_confirmed', 1)->get();
        $confirmedReservationCount = 0;
        foreach($confirmedReservations as $res)
            $confirmedReservationCount += $res->number_of_people;

        $freeSeats = ConferenceService::capacityLeft($id);
        $seatCount = $conference->capacity;



        $reservationsViewModels = [];
        foreach ($reservations as $res) {
            $user = $res->user()->first();
            array_push($reservationsViewModels, [
                'reservationId' => $res->id,
                'userId' => $user->id,
                'userName' => $user->name.' '.$user->surname,
                'count' => $res->number_of_people,
                'confirmed' => $res->is_confirmed,
            ]);

        }

        return [
            'conferenceId' => $id,
            'reservationsCount' => $reservationCount,
            'confirmedCount' => $confirmedReservationCount,
            'freeSeats' => $freeSeats,
            'seatsCount' => $seatCount,
            'reservations' => $reservationsViewModels,
        ];
    }

    /**
     * Returns a conference by id and correctly formats time to be used in html forms
     *
     * @param  mixed $id ID of the conference
     * @return Conference Conference or null if not found
     */
    public static function getWithFormattedDate($id) {
        $conference = Conference::find($id);
        if($conference !== null) {
            $conference->start_time= $conference->start_time->format('Y-m-d\TH:i');
            $conference->end_time = $conference->end_time->format('Y-m-d\TH:i');
        }
        return $conference;
    }

    /**
     * Returns all conferences of a user
     *
     * @param  mixed $id ID of user
     * @return void Array of conferences
     */
    public static function getMy($id) {
        return Conference::where('owner_id', $id)->get();
    }

    /**
     * Validated HTTP POST request and creates new conference
     *
     * @param  Request $request HTTP POST request
     * @return String Returns empty string if no error occurred, others a error message
     * will be returned
     */
    public static function create(Request $request) {

        $validator =  Validator::make($request->all(), self::VALIDATOR_PARAMS);

        if($validator->fails()) {
            $errorMessages = collect($validator->errors()->toArray())
                ->flatten()
                ->implode("\n");


            return $errorMessages;
        }

        $validated = $validator->validated();

        $conference = Conference::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'theme' => $validated['theme'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'place_address' => $validated['place_address'],
            'price' => $validated['price'],
            'capacity' => $validated['capacity'],
            'poster' => $validated['poster'],
            'bank_account' => $validated['bank_account'],
            'owner_id' => auth()->user()->id,
        ]);

        return ''; // everything was alright, return no error message
    }

    /**
     * Validated HTTP POST request and edits existing conference
     *
     * @param  Request $request HTTP POST request
     * @return String Returns empty string if no error occurred, others a error message
     * will be returned
     */
    public static function edit(Request $request) {
        $validator =  Validator::make($request->all(), self::VALIDATOR_PARAMS);

        if($validator->fails()) {
            $errorMessages = collect($validator->errors()->toArray())
                ->flatten()
                ->implode("\n");

            return $errorMessages;
        }

        $validated = $validator->validated();

        $id = $request->input('id');
        $conference = Conference::find($id);

        if($conference == null) {
            return "Error: provided conference doesn't exist";
        }

        $conference->title = $validated['title'];
        $conference->description = $validated['description'];
        $conference->theme = $validated['theme'];
        $conference->place_address = $validated['place_address'];
        $conference->price = $validated['price'];
        $conference->capacity = $validated['capacity'];
        $conference->poster = $validated['poster'];
        $conference->bank_account = $validated['bank_account'];

        if(self::canBeModified($id) === false) {
            if( $conference->start_time != $validated['start_time'] ||
                $conference->end_time == $validated['end_time']) {

                $conference->save();
                return 'Changes were changed expect for time: Conference has confirmed reservations'; // everything was alright, return no error message
            }
        }

        $conference->start_time = $validated['start_time'];
        $conference->end_time = $validated['end_time'];
        $conference->save();
        return ''; // everything was alright, return no error message
    }


    /**
     * Returns empty conference with date set to now
     *
     * @return Conference
     */
    public static function emptyConferenceWithDate() : Conference {
        $conference = new Conference();
        $conference->start_time = now();
        $conference->end_time = now();
        return $conference;
    }

    /**
     * Returns number of free seats/capacity for conference with given id
     *
     * @param  mixed $id ID of the conference
     * @return int number of free seats/capacity
     */
    public static function capacityLeft($id) : int {
        $conference = Conference::find($id);

        if($conference == null) {
            return -1;
        }

        $capacityMax = $conference->capacity;
        $capacityCurrent = 0;

        foreach($conference->reservations as $reservation) {
            $capacityCurrent += $reservation->number_of_people;
        }

        return $capacityMax - $capacityCurrent;
    }

    /**
     * Edits lectures to be confirmed or unconfirmed based on information in
     * $request
     *
     * @param  Request $request POST request containing form information
     * @return bool returns true on success
     */
    public static function editLecturesList(Request $request) {
        $id = $request->input('id');
        $conference = Conference::find($id);

        foreach($conference->lectures as $lectures) {
            $val = $request->input((string)($lectures->id));

            if($lectures->is_confirmed != ($val == 'true')) {
                $lectures->is_confirmed = ($val == 'true');
                $lectures->save();
            }
        }

        $conference->save();

        return true;
    }

    /**
     * Confirms reservation
     * $request
     *
     * @param int $conferenceId - Id of the conference
     * @param int $reservationId - Id of the reservation
     * @return bool returns true on success
     */
    public static function confirmReservation($conferenceId, $reservationId) {
        $conference = Conference::find($conferenceId);
        if (!$conference)
            return 'Conference does not exist.';

        $currentUserId = auth()->user()->id;
        if ($conference->owner_id != $currentUserId)
            return 'You are not allowed to confirm reservations for this conference!';

        $reservation = Reservation::find($reservationId);
        if (!$reservation)
            return 'Reservation does not exist.';

        if ($reservation->conference_id != $conferenceId)
            return 'You are not allowed to confirm this reservation!';

        if ($reservation->is_confirmed)
            return 'Reservation was already confirmed';

        $reservation->update(['is_confirmed' => true]);
        return null;
    }

    /**
     * Returns the list of all rooms for the specified conference.
     *
     * @param  $conferenceId - id of the conference
     * @return list ruturns list of model views of the conference with useful attributes
     */
    public static function listRooms($conferenceId) {
        $conference = Conference::find($conferenceId);
        if (!$conference)
            return null;

        $rooms = $conference->rooms()->get();
        $confirmedLectures = $conference->lectures()
            ->where('is_confirmed', 1)
            ->get();

        $roomViewModels = [];
        foreach ($rooms as $room) {
            $isRoomUsed = $confirmedLectures->contains('room_id', $room->id);
            array_push($roomViewModels, [
                'id' => $room->id,
                'name' => $room->name,
                'canBeDeleted' => !$isRoomUsed,
            ]);
        }

        return $roomViewModels;
    }

    public static function delete($id) {
        $conference = Conference::find($id);

        if($conference === null) {
            return "Conference was not found";
        }

        if(self::canBeModified($id) === false) {
            return "Conference cannot be deleted: Conferences has confirmed reservations";
        }

        if(Carbon::parse($conference->end_time)->isFuture()) {
            $reservations = Reservation::where('conference_id', $id)
                ->where('is_confirmed', true)
                ->get();

            if($reservations->count() !== 0) {
                return "Conference cannot be deleted: Conferences has confirmed reservations";
            }
        }

        // $conference->delete();

        return "Conference was delete successfully";
    }

    /**
     * Creates new room
     *
     * @param  $conferenceId - id of the conference
     * @param  $roomName - name of new room
     * @return void
     */
    public static function createRoom($conferenceId, $roomName) {
        Room::create([
            'name' => $roomName,
            'conference_id' => $conferenceId,
        ]);
    }

    /**
     * Deletes specified room
     *
     * @param  $conferenceId - id of the conference
     * @param  $roomName - name of new room
     * @return void
     */
    public static function deleteRoom($conferenceId, $roomId) {
        $room = Room::find($roomId);
        if ($room->conference_id != $conferenceId)
            return false;

        $room->delete();
        return true;
    }


    /**
     * Updates specified room
     *
     * @param mixed $conferenceId - id of the conference
     * @param mixed $roomId - id of the room
     * @param mixed $newName - new name of the room
     * @return void
     */
    public static function updateRoom($conferenceId, $roomId, $newName) {
        $room = Room::find($roomId);
        if ($room->conference_id != $conferenceId)
            return false;

        $room->update([
            'name' => $newName,
        ]);
        return true;
    }

    private static function canBeModified($id) {
        $conference = Conference::find($id);
        if(Carbon::parse($conference->end_time)->isFuture()) {
            $reservations = Reservation::where('conference_id', $id)
                ->where('is_confirmed', true)
                ->get();

            if($reservations->count() !== 0) {
                return false;
            }
        }

        return true;
    }
}
