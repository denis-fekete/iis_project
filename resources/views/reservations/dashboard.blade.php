@extends('layouts.layout')
<link href="{{asset('css/reservation.css')}}" rel="stylesheet">

@section('content')

<h2>Reservations:</h2>

@foreach ($reservations as $reservation)
    <div class="reservation">
        <p class="conference_title">Title: {{ $reservation->conference->title }}</p>
        <p class="conference_time"> Time:
            From {{ $reservation->conference->start_time }}
            To {{ $reservation->conference->end_time }}
            </p>
        <p class="reservation_number_of_peole">Number of people: {{ $reservation->number_of_people }}</p>
        <button onclick="cancelReservation({{ $reservation->id }})">Cancel reservation</button>
        <br>
        <button onclick="showSchedule({{ $reservation->id }})">Show schedule</button>
    </div>
    <br>
@endforeach

@endsection
