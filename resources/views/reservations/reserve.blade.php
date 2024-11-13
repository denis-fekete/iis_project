@extends('layouts.layout')

@section('content')
<link href="/css/registration.css" rel="stylesheet">

@if ($user == null)
    <h3>You are not logged in, please do so or register to continue reservation</h3>
@endif

<div>
    <form action="{{ url('/reservations/reserve') }}" id="register_form" class="register_form" method="post">
        @csrf
        <input class="form_input" type="hidden" name="conferenceId" id="id" value="{{ $conferenceId }}" required>
        <br>
        <label class="form_label" for="password">Number of people:</label>
        <input class="form_input" type="number" name="number_of_people" id="no_people" max="{{$max}}" min="0" required>
        <br>
        @if ($user == null)
            <button type"submit" disabled>Make reservation</button>
        @else
            <button type"submit">Make reservation</button>
        @endif
        <br>
    </form>
</div>

@if ($user == null)
    <button type="button" onclick="showLogin()">Log In or Register</button>
@endif

@endsection

