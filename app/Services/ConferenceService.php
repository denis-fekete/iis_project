<?php

namespace App\Services;


use App\Models\Conference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ConferenceService
{
    const MAX_DESCRIPTION_LEN = 160;


    /**
     * Returns all conferences in database
     *
     * @return Collection of Conferences
     */
    public function getAll(): Collection {
        return Conference::all();
    }

    /**
     * Returns all conferences in database but the description length is capped
     *
     * @return Collection of Conferences with capped length of description
     */
    public function getAllShortDescription(): Collection {
        $conferences = $this->getAll();

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
    public function get($id) {
        return Conference::find($id);
    }

    /**
     * Returns a conference by id and puts owner id and name into it
     *
     * @param  mixed $id ID of the conference
     * @return Conference Conference or null if not found
     */
    public function getAllDetails($id) {
        // add owner and lectures info now for less database requests
        return Conference::with('owner:id,name,surname')
            ->with('lectures.lecturer:name')
            ->find($id);
    }

    /**
     * Returns a conference by id and correctly formats time to be used in html forms
     *
     * @param  mixed $id ID of the conference
     * @return Conference Conference or null if not found
     */
    public function getWithFormattedDate($id) {
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
    public function getMy($id) {
        return Conference::where('owner_id', $id)->get();
    }

    /**
     * Validated HTTP POST request and creates new conference if not problem occurred,
     * if error with validation occurred an error message will be returned
     *
     * @param  Request $request HTTP POST request
     * @return String Returns empty string if no error occurred, others a error message
     * will be returned
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
                'title' => 'required|min:3|max:100',
                'description' => 'required|min:20|max:10000',
                'theme' => 'required|min:2|max:100',
                'start_time' => 'required|date',
                'end_time' => 'required|date',
                'place_address' => 'required',
                'price' => 'required',
                'capacity' => 'required',
        ]);

        if($validator->fails()) {
            $errorMessages = collect($validator->errors()->toArray())
                ->flatten()
                ->implode("\n"); // Joins each error with a newline


            return $errorMessages;
        }

        $validated = $validator->validated();

        $conference = Conference::create([
            'title' => $validated['title'],
            'description' => $validated['description'], // must be present, must be email, must unique in user in column email
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
     * Returns empty conference with date set to now
     *
     * @return Conference
     */
    public function emptyConferenceWithDate() : Conference {
        $conference = new Conference();
        $conference->start_time = now();
        $conference->end_time = now();
        return $conference;
    }

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

    public function editLecturesList(Request $request) {
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
}
