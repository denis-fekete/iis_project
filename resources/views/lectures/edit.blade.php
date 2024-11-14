@extends('layouts.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')
<form action="{{ url("lectures/editSave/{$info['id']}") }}" id="register_form" class="register_form" method="post">
    @csrf
    <label class="form_label" for="title">Tiltle:</label>
    <input class="form_input" type="text" name="title" id="title" value="{{$info["title"]}}" required>
    <br>
    <label class="form_label" for="title">Poster URL:</label>
    <input class="form_input" type="url" name="poster" id="poster" value="{{$info["poster"]}}" required>
    <br>
    <label class="form_label" for="stat_time">Start time:</label>
    <input class="form_input" type="datetime-local" name="start_time" id="start_time" value="{{$info["start_time"]}}" required>
    <br>
    <label class="form_label" for="end_time">End time:</label>
    <input class="form_input" type="datetime-local" name="end_time" id="end_time" value="{{$info["end_time"]}}" required>
    <br>
    <button type="submit">Save</button>
    <br>
</form>
@endsection
