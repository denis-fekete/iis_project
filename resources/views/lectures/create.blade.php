@extends('layouts.layout')

@section('content')
<form action="{{ url("lectures/create") }}" id="register_form" class="register_form" method="post">
    @csrf
    <input type="hidden" name="conference_id" value="{{ $info['conference_id'] }}">
    <label class="form_label" for="title">Tiltle:</label>
    <input class="form_input" type="text" name="title" id="title" value="{{$info["title"]}}" required>
    <br>
    <label class="form_label" for="poster">Poster URL:</label>
    <input class="form_input" type="url" name="poster" id="poster" value="{{$info["poster"]}}">
    <br>
    <label class="form_label" for="description">Decription:</label>
    <input class="form_input" type="text" name="description" id="description" value="{{$info["description"]}}" required>
    <br>
    <!--<br>
    <label class="form_label" for="stat_time">Start time:</label>
    <input class="form_input" type="datetime-local" name="start_time" id="start_time" value="{{$info["start_time"]}}" required>
    <br>
    <label class="form_label" for="end_time">End time:</label>
    <input class="form_input" type="datetime-local" name="end_time" id="end_time" value="{{$info["end_time"]}}" required>
    <br> -->
    <button type="submit">Offer lecture</button>
    <br>
</form>

<script>
@endsection
