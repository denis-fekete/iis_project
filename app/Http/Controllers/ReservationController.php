<?php

namespace App\Http\Controllers;

use App\Models\Reservation as ModelsReservation;
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
    public function getForm() {
        $id = request()->get('id');

        // TODO: optimize
        $max = DB::table('conference')->where('id', $id)->get('capacity');
        $current =  DB::table('reservation')->where('conference_id', $id)->count();

        return view('/reservations/reserve')
            ->with('id', $id)
            // ->with('max', $max - $current);
            ->with('max', 100);
    }

    public function create() {
        $id = request()->input('id');

        // TODO: validate data

        // ModelsReservation::create([
        //     "user_id" => auth()->user()->id,
        //     "conference_id" => (string)$id,
        //     "is_confirmed" => false,
        // ]);

        return redirect('/conferences/conference?id=' . $id)
            ->with('notification', 'Reservation was created successfully');
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
