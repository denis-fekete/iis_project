@extends('layout.layout')
<link href="{{asset('css/reservation.css')}}" rel="stylesheet">

@section('content')

@foreach ($reservations as $reservation)
    <div class="reservation">
        <p>Reservation:</p>
        <p class="conference_title">Title: {{ $reservation->conference_id }}
        <button onclick="cancelReservation({{ $reservation->id }})">Cancel reservation</button>
    </div>
    <br>
@endforeach
@endsection
