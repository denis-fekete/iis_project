@extends('layouts.layout')

@section('content')

<div class="card">
    <p>There are <b>{{$max}}</b> available places for this conference.</p>
    <form action="{{ url('/reservations/reserve') }}" id="register_form" class="reservation-form" method="post">
        @csrf
        <input class="form_input" type="hidden" name="conference_id" id="id" value="{{ $conferenceId }}" required>
        <br>
        <label class="form_label" for="password">Number of people:</label>
        <input class="form_input" type="number" name="number_of_people" id="no_people" max="{{$max}}" min="1" value="{{old('number_of_people')}}" required>

        @if ($user === null)
            <br>
            <div>
                <label class="form_label" for="email">*Email:</label>
                <input class="form_input" type="email" name="email" id="email" value="{{old('email')}}" required>
                <br>
                <label class="form_label" for="password">*Password:</label>
                <input class="form_input" type="password" name="password" id="password" required>
                <br>
                <label class="form_label" for="password_confirmation">*Confirm password:</label>
                <input class="form_input" type="password" name="password_confirmation" id="password_confirmation"required>
                <br>
                <p><i>* mandatory inputs</i></p>
            </div>
            <br>
            <p class="title_2">Please fill out your credentials, a new account will be created.</p>
            <br>
        @endif
        <button type"submit">Make reservation</button>
    </form>
</div>

@endsection

