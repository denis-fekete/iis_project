@extends('layouts.layout')

@section('content')

<div class="card">
    <p>There are <b>{{$max}}</b> available places for this conference.</p>
    <form action="{{ url('/reservations/reserve') }}" id="register_form" class="reservation-form" method="post">
        @csrf
        <input class="form_input" type="hidden" name="conferenceId" id="id" value="{{ $conferenceId }}" required>
        <br>
        <label class="form_label" for="password">Number of people:</label>
        <input class="form_input" type="number" name="number_of_people" id="no_people" max="{{$max}}" min="1" required>
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
    <br>
    <p class="title_2">You are not logged in, in order to continue with reservation you must Log in or create a new account, your progress will be saved</p>
    <br>
@endif
@if ($user == null)
    <button type="button" onclick="showLogin()">Log In or Register</button>
@endif

@endsection

