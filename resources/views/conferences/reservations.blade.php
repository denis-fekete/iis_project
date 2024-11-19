@extends('layouts.layout')

@section('content')

@isset($info['role'])
    @if($info['role'] == 'admin')
        <div id='admin_content'>
            @yield('admin_content')
        <div>
    @endif
@endisset

<h2>Reservations</h2>
<form action="{{ url('conferences/conference/reservations') }}" id="register_form" class="register_form" method="post">
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
@endsection
