<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Services\ConferenceService;
use App\Services\LectureScheduleService;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * Collects data for reservation form
     * @param int $id id of the conference reservation will be created for
     * @return view reservation form view with data ($conferenceId, $max, $user)
     */
    public function getForm($id) {
        $conference = Conference::find($id);
        if (!$conference)
        return redirect('/conferences/search')
            ->withErrors(['notification' => ['Conference does not exist!']]);

        return view('/reservations/reserve')
            ->with('conferenceId', $id)
            ->with('max', ConferenceService::capacityLeft($id))
            ->with('user', auth()->user());
    }

    /**
     * Creates new reservation
     * @param Request $request data of new reservation (conferenceId, number_of_people)
     * @return redirect refirects user to reservations dashboard
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'conferenceId' => 'required|int',
            'number_of_people' => 'required|int|min:1',
        ]);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        $userId = auth()->user()->id;
        $err = ReservationService::create($request, $userId);

        if($err == '') {
            return redirect('/reservations/dashboard')
                ->with('notification', ['Reservation was created successfully']);
        } else {
            return redirect()->back()
                ->withErrors($err);
        }
    }

    /**
     * Returns all reservations in system
     *
     * @return Collection of reservations
     */
    public function getAll() {
        $id = auth()->user()->id;

        $reservations = ReservationService::getUserReservations($id);

        return view('reservations.dashboard')
            ->with('reservations', $reservations);
    }

    /**
     * Returns reservation by id
     * @param int $id id of the reservation
     * @return view view of the reservation
     */
    public function get($id) {
        $reservation = ReservationService::get($id);

        if($reservation == null) {
            return view('unknown');
        }

        return view('reservations.reservation')
            ->with('reservation', $reservation);
    }

    /**
     * Cancels specified reservation
     * @param int $id id of the reservation
     * @return view updated reservation dashboard
     */
    public function cancel($id) {
        $res = ReservationService::cancel(auth()->user()->id, $id);

        if($res == '') {
            return redirect('/reservations/dashboard')
                ->with('notification', ['Reservation was deleted successfully']);
        } else {
            return redirect('/reservations/dashboard')
                ->withErrors(['auth' => $res]);
        }
    }

    /**
     * Shows reservation's schedule
     * @param int $id id of the reservation
     * @return view schedule of the reservation
     */
    public function showSchedule($id) {
        $viewModels = LectureScheduleService::getLectureSchedule($id);
        if (!$viewModels)
            return redirect('/reservations/dashboard')->with('notification', ['Failed to open schedule!']);

        return view('reservations.schedule')
            ->with('schedule', $viewModels)->with('reservationId', $id);
    }

    /**
     * Saves reservation's schedule
     * @param Request $request updated schedule data
     * @return view schedule of the reservation
     */
    public function saveSchedule(Request $request) {
        $result = LectureScheduleService::saveLectureSchedule(
            $request->input('reservationId'),
            $request->input('scheduled') ?? [],
        );

        if (!$result)
            return redirect()->back()->with('notification', ['Schedule saved successfully!']);

        return redirect()->back()->with('notification', [$result]);
    }
}
