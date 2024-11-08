@extends('layout.layout')

@section('content')
<link href="/css/registration.css" rel="stylesheet">

<div>
    <form action="{{ url('/reservations/reserve') }}" id="register_form" class="register_form" method="post">
        @csrf
        <input class="form_input" type="hidden" name="conferenceId" id="id" value="{{ $conferenceId }}" required>
        <br>
        <label class="form_label" for="password">Number of people:</label>
        <input class="form_input" type="number" name="number_of_people" id="no_people" max="{{$max}}" min="0" required>
        <br>
        <button type"submit">Make reservation</button>
        <br>
    </form>
</div>
@endsection

