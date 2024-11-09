@extends('layout.layout')

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
    <h3>Log in</h3>
    <div>
        <form action="{{ url('login') }}" id="register_form" class="register_form" method="post">
            @csrf
            <label class="form_label" for="email">Email:</label>
            <input class="form_input" type="email" name="email" id="email"required>
            <br>
            <label class="form_label" for="password">Password:</label>
            <input class="form_input" type="password" name="password" id="password"required>
            <br>
            <button type"submit">Login</button>
            <br>
        </form>
    </div>

    <h3>Or register</h3>
    <div>
        <form action="{{ url('register') }}" id="register_form" class="register_form" method="post">
            @csrf
            <label class="form_label" for="email">Email:</label>
            <input class="form_input" type="email" name="email" id="email"required>
            <br>
            <label class="form_label" for="name">Name:</label>
            <input class="form_input" type="text" name="name" id="name"required>
            <br>
            <label class="form_label" for="surname">Surname:</label>
            <input class="form_input" type="text" name="surname" id="surname"required>
            <br>
            <label class="form_label" for="password">Password:</label>
            <input class="form_input" type="password" name="password" id="password"required>
            <br>
            <label class="form_label" for="password_confirmation">Confirm password :</label>
            <input class="form_input" type="password" name="password_confirmation" id="password_confirmation"required>
            <br>
            <button type"submit">Register</button>
            <br>
        </form>
    </div>
@endif

@endsection

