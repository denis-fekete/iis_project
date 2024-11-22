@extends('layouts.layout')

@section('content')
<div class="card">
    <form action="{{ url("lectures/editSave") }}" id="register_form" class="register_form" method="post">
        @csrf
        <input type="hidden" name="id" value="{{ $info['id'] }}">
        <label class="form_label" for="title">Tiltle:</label>
        <input class="form_input" type="text" name="title" id="title" value="{{$info["title"]}}">
        <br>
        <label class="form_label" for="poster">Poster URL:</label>
        <input class="form_input" type="url" name="poster" id="poster" value="{{$info["poster"]}}">
        <br>
        <label class="form_label" for="description">Description:</label>
        <input class="form_input" type="text" name="description" id="description" value="{{$info["description"]}}" required>
        <br>
        <button type="submit">Save</button>
        <br>
    </form>
</div>
@endsection
