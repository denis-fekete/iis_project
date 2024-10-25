@extends('layout.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')

@if ($notification != null)
    <div class="notification">
        <h3>{{$notification}}</h3>
    </div>
@endif

<form action="{{ url('conferences/create') }}" id="register_form" class="register_form" method="post">
@csrf
@foreach ($lectures as $item)
    @php
    @endphp
    <p>Lecture: {{$item->title}}:</p>
    <p>confirmed/not confirmed</p>
    <input class="form_input" type="radio" name="{{$item->id}}" id="capacity" required>
    <input class="form_input" type="radio" name="{{$item->id}}" id="capacity" required>
    <hr>
@endforeach
<button type"submit">Save</button>
</form>
@endsection
