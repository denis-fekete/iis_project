<?php

namespace App\Http\Controllers;

use App\Models\Reservation as ModelsReservation;
use App\Services\ConferenceService;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class Reservation {
    public int $id;
    public bool $is_confirmed;
    public string $conference_id;
    public string $user_id;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->is_confirmed = fake()->boolean();
        $this->conference_id = fake()->words(3, true);
        $this->user_id = fake()->name();
    }
}

class ReservationController extends Controller
{
    public function __construct(private ReservationService $reservationService)
    {

    }


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

        $err = $this->reservationService->create($request, $userId);

        if($err == '') {
            return redirect('/conferences/conference/' . $conferenceId)
                ->with('notification', 'Reservation was created successfully');
        } else {
            return redirect()->back()
                ->withErrors($err);
        }

    }

    public function getAll() {

        $count = fake()->numberBetween(1, 5);
        $reservations = [];

        for($i = 0; $i < $count; $i++) {
            $reservations[] = new Reservation($i);
        }

        return view('reservations.dashboard')
            ->with('reservations', $reservations);
    }

    public function cancel($id) {
        //TODO: cancel reservation

        return redirect('/reservations')
            ->with('notification', 'Reservation was deleted successfully');
    }
}
