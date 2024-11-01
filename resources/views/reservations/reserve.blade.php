@extends('layout.layout')

@section('content')
<link href="/css/registration.css" rel="stylesheet">
<script src="/httpHandler.js"></script>

<div>
    @php
        $urlLink = "reservations/reserve?id=" . $id;
    @endphp

    <form action="{{ url($urlLink) }}" id="register_form" class="register_form" method="post">
        @csrf
        <input class="form_input" type="hidden" name="id" id="id" value="{{ $id }}" required>
        <br>
        <label class="form_label" for="password">Number of people:</label>
        <input class="form_input" type="number" name="no_people" id="no_people" max="{{$max}}" min="0" required>
        <br>
        <button type"submit">Make reservation</button>
        <br>
    </form>
</div>
@endsection

