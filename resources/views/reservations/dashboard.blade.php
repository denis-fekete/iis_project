@extends('layouts.layout')

@section('content')

<div class="title_block">
    <p class="title">My reservations</h1>
</div>

<div class="grid_vertical">
@foreach ($reservations as $reservation)
    <div class="card">
        <p class="conference_title">Title: {{ $reservation->conference->title }}</p>
        <p class="conference_time"> Time:
            From {{ $reservation->conference->start_time }}
            To {{ $reservation->conference->end_time }}
            </p>
        <p class="reservation_number_of_peole">Number of people: {{ $reservation->number_of_people }}</p>
        <br>
        <div class="grid_horizontal">
        <button onclick="navigateTo('/reservations/cancel/{{ $reservation->id }}')">Cancel reservation</button>
        <button onclick="navigateTo('/reservations/schedule/{{ $reservation->id }}')">Show schedule</button>
        </div>
    </div>
@endforeach
</div>

@endsection
