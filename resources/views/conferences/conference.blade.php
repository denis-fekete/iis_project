@extends('layouts.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')
<div class="card">
    <button onclick="makeReservation('{{$conferences->id}}')">Make reservation</button>

    <p class="title">{{$conferences->title}}</p>
    <p class="description">{{$conferences->description}}</p>
    <p class="theme">{{$conferences->theme}}</p>
    <p class="date">{{$conferences->start_time}} - {{$conferences->end_time}}</p>
    <p class="price">Price per person: {{$conferences->price}}Kč</p>
    <p class="capacity">Capacity: {{$conferences->capacity}}</p>
    <p class="owner">Organizer: {{$conferences->owner->name}} {{$conferences->owner->surname}}</p>
    <hr>
    <p>Lectures:</p>
    @foreach ($conferences->lectures as $item)
        <div class="lecture">
            <p class="lecture_title">Title: {{$item->title}}</p>
            <p class="lecture_poster">{poster:<{{$item->poster}}>}</p>

            <p class="lecture_speaker" onclick="searchForPerson( {{$item->lecturer->id}} )">
                Speaker: {{$item->lecturer->name}} {{$item->lecturer->surname}}
            </p>

            <p class="lecture_confirmed">Confirmed: {{
                    $item["is_confirmed"] ? 'yes' : 'no'
                }}
            </p>
        <p class="lecture_time">Time from {{ $item["start_time"] }} to {{ $item["end_time"] }}</p>
            <div class="room">
                <p class="room_id">Room id: {{$item["room_id"]}}</p>
                <p class="room_name">Room name: TBD</p>
                <p class="room_address">Room address: TBD</p>
            </div>
        </div>
        <br>
    @endforeach
</div>
@endsection

