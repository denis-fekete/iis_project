<?php

namespace App\Services;

use App\Enums\OrderBy;
use App\Enums\Themes;
use App\Enums\OrderDirection;
use App\Models\Conference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ConferenceService
{
    const MAX_DESCRIPTION_LEN = 160;

    private const VALIDATOR_PARAMS = [
        'title' => 'required|min:3|max:100',
        'description' => 'required|min:20|max:10000',
        'theme' => 'required|min:2|max:100',
        'start_time' => 'required|date',
        'end_time' => 'required|date',
        'place_address' => 'required',
        'price' => 'required',
        'capacity' => 'required',
    ];

    /**
     * Returns all conferences in database
     *
     * @param  Themes|null $themes
     * @param  OrderBy|null $orderBy
     * @param  OrderDirection|null $orderDir
     * @return Collection
     */
    public static function getAll($themes, $orderBy, $orderDir): Collection {
        $query = Conference::query();

        // TODO: rework to allow more simultaneously
        $allThemes = Themes::cases();

        if($themes != Themes::All->value) {
            foreach($allThemes as $item) {
                if($item->value == $themes) {
                    $query->where('theme', $item->value);
                    break;
                }
            }
        }

        switch($orderDir) {
            case OrderDirection::Descending->value:
            case OrderDirection::Ascending->value:
                break;
            default:
                error_log("Unknown order direction");
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
                error_log("Unknown order by type");
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
    public static function getAllShortDescription($themes, $orderBy, $orderDir): Collection {
        $conferences = self::getAll($themes, $orderBy, $orderDir);

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
     * @return Conference Conference or null if not found
     */
    public static function getWithReservations($id) {
        // add owner and lectures info now for less database requests
        return Conference::with('reservations.user')
            ->find($id);
    }

    /**
     * Returns a conference by id and correctly formats time to be used in html forms
     *
     * @param  mixed $id ID of the conference
     * @return Conference Conference or null if not found
     */
    public static function getWithFormattedDate($id) {
        $conference = Conference::find($id);
        $conference->start_time= $conference->start_time->format('Y-m-d\TH:i');
        $conference->end_time = $conference->end_time->format('Y-m-d\TH:i');

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
        $conference->start_time = $validated['start_time'];
        $conference->end_time = $validated['end_time'];
        $conference->place_address = $validated['place_address'];
        $conference->price = $validated['price'];
        $conference->capacity = $validated['capacity'];

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
     * Edits reservations to be confirmed or unconfirmed based on information in
     * $request
     *
     * @param  Request $request POST request containing form information
     * @return bool returns true on success
     */
    public static function editReservationsList(Request $request) {
        $id = $request->input('id');
        $conference = Conference::find($id);

        foreach($conference->reservations as $reservation) {
            $val = $request->input((string)($reservation->id));

            if($reservation->is_confirmed != ($val == 'true')) {
                $reservation->is_confirmed = ($val == 'true');
                $reservation->save();
            }
        }

        $conference->save();

        return true;
    }
}
