@extends('layout.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')

@if ($notification != null)
    <div class="notification">
        <h3>{{$notification}}</h3>
    </div>
@endif

<h2>Lectures</h2>
<form action="{{ url('conferences/lectures') }}" id="register_form" class="register_form" method="post">
<input type="number" name="id" value="{{ $id }}" hidden required>
@csrf
@foreach ($lectures as $item)
    <p>Lecture: {{$item->title}}:</p>
    <p>confirmed/not confirmed</p>
    <input class="form_input" type="radio" name="{{$item->id}}" value="true"
        @if ($item->is_confirmed)
            checked
        @endif
        required>
    <input class="form_input" type="radio" name="{{$item->id}}" value="false"
        @if (!($item->is_confirmed))
            checked
        @endif
        required>
    <hr>
@endforeach
<button type"submit">Save</button>
</form>
@endsection
