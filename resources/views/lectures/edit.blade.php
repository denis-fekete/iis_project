@extends('layout.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')
<form action="{{ url('conferences/create') }}" id="register_form" class="register_form" method="post">
    @csrf
    <label class="form_label" for="title">Tiltle:</label>
    <input class="form_input" type="text" name="title" id="title" value="{{$info["title"]}}" required>
    <br>
    <label class="form_label" for="poster">Poster:</label>
    <input class="form_input" type="text" name="poster" id="poster" value="{{$info["poster"]}}">
    <br>
    <label class="form_label" for="stat_time">Start time:</label>
    <input class="form_input" type="date" name="start_time" id="start_time" required>
    <br>
    <label class="form_label" for="end_time">End time:</label>
    <input class="form_input" type="date" name="end_time" id="end_time" required>
    <br>
    <p>TODO: room selection</p>
    <button type"submit">Create</button>
    <br>
</form>
@endsection
