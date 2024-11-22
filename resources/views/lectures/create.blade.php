@extends('layouts.layout')

@section('content')
<div class="card">
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
        <textarea class="form_input" type="textarea" name="description" id="description" required>
            {{$info["description"]}}
        </textarea>
        <br>
        <button type="submit">Offer lecture</button>
        <br>
    </form>
</div>
@endsection
