<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function create() {
        // TODO: create reservation
        $id = request()->get('id');

        return redirect('/conferences/conference?id=' . $id)
            ->with('notification', 'Reservation was created successfully');
    }

    public function get() {

        $count = fake()->numberBetween(1, 5);
        $reservations = [];

        for($i = 0; $i < $count; $i++) {
            $reservations[] = new Reservation($i);
        }

        return view('reservations.reservations')
            ->with('reservations', $reservations);
    }

    public function cancel() {
        $id = request()->get('id');

        //TODO: cancel reservation

        return redirect('/reservations')
            ->with('notification', 'Reservation was deleted successfully');
    }
}
