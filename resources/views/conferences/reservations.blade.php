@extends('layouts.layout')

@section('content')

@php
    $url = 'conferences/conference/reservations';
@endphp

<div class="title_block">
    @isset($info['role'])
        @if($info['role'] == 'admin')
            <br>
            <p class="title">(Editing as administrator)</p>
            @php
                $url = 'admin/conferences/conference/reservations';
            @endphp
        @endif
    @endisset

    <p class="title">Conference reservations</p>
</div>

<div class="card">
<form action="{{ url($url) }}" id="register_form" class="register_form" method="post">
<input type="number" name="id" value="{{ $id }}" hidden required>
@csrf
@foreach ($reservations as $reservation)
    <p>Username: {{$reservation->user->name}} {{$reservation->user->surname}}</p>
    <p>Number of people: {{$reservation->number_of_people}}</p>
    <p>confirmed/not confirmed</p>
    <input class="form_input" type="radio" name="{{$reservation->id}}" value="true"
        @if ($reservation->is_confirmed)
            checked
        @endif
        required>
    <input class="form_input" type="radio" name="{{$reservation->id}}" value="false"
        @if (!($reservation->is_confirmed))
            checked
        @endif
        required>
    <hr>
@endforeach
<button type"submit">Save</button>
</form>
<div>
@endsection
