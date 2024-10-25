@extends('layout.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')
<div class="card">
    <button onclick="makeReservation('{{$id}}')">Make reservation</button>

    <p class="title">{{$title}}</p>
    <p class="description">{{$description}}</p>
    <p class="theme">{{$theme}}</p>
    <p class="date">{{$start_time}} - {{$end_time}}</p>
    <p class="price">{{$price}}Kč</p>
    <p class="capacity">Capacity: {{$capacity}}Kč</p>
    <p class="owner">Organizer: {{$owner_name}}</p>
    <hr>
    <p>Lectures:</p>
    @foreach ($lectures as $item)
        <div class="lecture">
            <p class="lecture_title">Title: {{$item["title"]}}</p>
            <p class="lecture_poster">{poster:<{{$item["title"]}}>}</p>

            <p class="lecture_speaker" onclick="searchForPerson( {{$item['speaker_id']}} )">
                Speaker: {{$item["speaker_name"]}}
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

