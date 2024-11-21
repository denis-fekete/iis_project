@extends('layouts.layout')

@section('content')

<div class="title_block">
    <p class="title">My reservations</h1>
</div>

<div class="grid_vertical">
@foreach ($reservations as $reservation)
    <div class="card">
        <p class="title_2" >Conference: {{ $reservation->conference->title }}</p>
        <p> From: {{ $reservation->conference->start_time }} </p>
        <p> To: {{ $reservation->conference->end_time }}</p>
        <p>Number of people: {{ $reservation->number_of_people }}</p>
        <p>Confirmed:
            @if($reservation->is_confirmed == 1)
                Yes
            @else
                No
            @endif
        </p>
        <br>
        <div class="grid_horizontal">
        <button onclick="navigateTo('/reservations/cancel/{{ $reservation->id }}')">Cancel reservation</button>
        <button onclick="navigateTo('/reservations/schedule/{{ $reservation->id }}')">Show schedule</button>
        </div>
    </div>
@endforeach
</div>

@endsection
