@extends('layout.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')
<form action="{{ url('conferences/create') }}" id="register_form" class="register_form" method="post">
    @csrf
    <label class="form_label" for="title">Tiltle:</label>
    <input class="form_input" type="text" name="title" id="title" required>
    <br>
    <label class="form_label" for="description">Description:</label>
    <input class="form_input" type="text" name="description" id="description" required>
    <br>
    <label class="form_label" for="theme">Theme:</label>
    <input class="form_input" type="text" name="theme" id="theme" required>
    <br>
    <label class="form_label" for="stat_time">Start time:</label>
    <input class="form_input" type="date" name="start_time" id="start_time" required>
    <br>
    <label class="form_label" for="end_time">End time:</label>
    <input class="form_input" type="date" name="end_time" id="end_time" required>
    <br>
    <label class="form_label" for="address">Address:</label>
    <input class="form_input" type="address" name="place_address" id="address" required>
    <br>
    <label class="form_label" for="price">Price:</label>
    <input class="form_input" type="number" name="price" id="price" required>
    <br>
    <label class="form_label" for="capacity" >Capacity:</label>
    <input class="form_input" type="number" name="capacity" id="capacity" required>
    <br>
    <button type"submit">Create</button>
    <br>
</form>
@endsection
