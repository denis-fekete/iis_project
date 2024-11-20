<?php

namespace App\Http\Controllers;

use App\Models\Reservation as ModelsReservation;
use App\Services\ConferenceService;
use App\Services\LectureScheduleService;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class ReservationController extends Controller
{
    public function getForm($id) {

        return view('/reservations/reserve')
            ->with('conferenceId', $id)
            ->with('max', ConferenceService::capacityLeft($id))
            ->with('user', auth()->user());
    }

    public function create(Request $request) {
        if(auth()->user() == null) {
            return redirect()->back()
                ->withErrors(['auth' => 'Error: it seems that you are not logged in']);
        }

        $userId = auth()->user()->id;
        $conferenceId = $request->input('conferenceId');

        $err = ReservationService::create($request, $userId);

        if($err == '') {
            return redirect('/conferences/conference/' . $conferenceId)
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

        $reservations = ReservationService::getAllMyWithConferenceInfo($id);

        return view('reservations.dashboard')
            ->with('reservations', $reservations);
    }

    public function get($id) {
        $reservation = ReservationService::get($id);

        return view('reservations.reservation')
            ->with('reservation', $reservation);
    }

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

    public function showSchedule($id) {
        $viewModels = LectureScheduleService::getLectureSchedule($id);
        if (!$viewModels)
            return redirect('/reservations/dashboard')->with('notification', ['Failed to open schedule!']);

        return view('reservations.schedule')
            ->with('schedule', $viewModels)->with('reservationId', $id);
    }

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
