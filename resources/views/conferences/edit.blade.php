@extends('layouts.layout')

@section('content')

@php
    if($info['type'] == 'create') {
        $url = '/conferences/create';
    } else {
        $url = '/conferences/edit';
    }
@endphp

<form action="{{ url($url) }}" id="register_form" class="register_form" method="post">
    @csrf
    @isset($info)
        @isset($info['id'])
            <input class="text" type="text" name="id" id="id" value={{ $info['id'] }} hidden>
        @endisset
    @endisset

    <label class="form_label" for="title">Tiltle:</label>
    <input class="form_input" type="text" name="title" id="title"
        value="{{old('title', $conference->title)}}" required>
    <br>
    <label class="form_label" for="description">Description:</label>
    <input class="form_input" type="text" name="description" id="description"
        value="{{old('description', $conference->description)}}" required>
    <br>
    <label class="form_label" for="theme">Theme:</label>
    <input class="form_input" type="text" name="theme" id="theme"
        value="{{old('theme', $conference->theme)}}" required>
    <br>
    <label class="form_label" for="stat_time">Start time:</label>
    <input class="form_input" type="datetime-local" name="start_time" id="start_time"
        value="{{old('start_time', $conference->start_time)}}" required>
    <br>
    <label class="form_label" for="end_time">End time:</label>
    <input class="form_input" type="datetime-local" name="end_time" id="end_time"
        value="{{old('end_time', $conference->end_time)}}" required>
    <br>
    <label class="form_label" for="address">Address:</label>
    <input class="form_input" type="address" name="place_address" id="address"
        value="{{old('place_address', $conference->place_address)}}" required>
    <br>
    <label class="form_label" for="price">Price:</label>
    <input class="form_input" type="number" name="price" id="price"
        value="{{old('price', $conference->price)}}" required>
    <br>
    <label class="form_label" for="capacity" >Capacity:</label>
    <input class="form_input" type="number" name="capacity" id="capacity"
        value="{{old('capacity', $conference->capacity)}}" required>
    <br>

    @isset($info['type'])
        @if ($info['type'] == 'create')
            <button type"submit">Create new</button>
        @else
            <button type"submit">Save changes</button>
        @endif
    @endisset

    <br>
</form>
@endsection
