@extends('layouts.layout')

@section('content')
<div class="title_block">
    <p class="title">{{$conferences->title}}</h1>
</div>

<div class="card">
    @if ($conferences->poster)
        <img class="lecture-poster" src="{{$conferences->poster}}" alt="Placeholder image">
    @endif
    <br><br>
    <div class="horizontal_grid">
        <button onclick="navigateTo('/reservations/reserve/{{$conferences->id}}')">Make reservation</button>
        <button onclick="navigateTo('/lectures/create/{{$conferences->id}}')">Offer a lecture</button>
    </div>
    <br>
    <div class="horizontal_grid">
        <p>Organizer:
            <a class="text_link"
                onclick="navigateTo('/users/search/{{$conferences->owner->id}}')">
                {{$conferences->owner->name}} {{$conferences->owner->surname}}
                </a></p>

        <p>Theme: <a class="text_theme">{{$conferences->theme}}</a></p>
    </div>
    <br>
    <p>{{$conferences->description}}</p>
    <br>
    <p>Start date: {{$conferences->start_time}}</p>
    <p>End date: {{$conferences->end_time}}</p>
    <br>
    <p>Address: {{$conferences->place_address}}</p>
    <p>Capacity: {{$conferences->capacity}}</p>
    <p>Price per person: {{$conferences->price}}Kč</p>
    <br>
</div>
<br>
<div class="title_block">
    <p class="title">Lectures</h1>
</div>
@foreach ($conferences->lectures as $item)
    @if($item->is_confirmed == true)
        <div class="card">
            <p class="title_2" >{{$item->title}}</p>
            <br>
            @if ($item->poster)
                <img class="lecture-poster" src="{{$item->poster}}" alt="Placeholder image">
            @endif
            <br><br>
            <p>Speaker:
                <a class="text_link"
                    onclick="navigateTo('/users/search/{{$item->lecturer->id}}')">
                    {{$item->lecturer->name}} {{$item->lecturer->surname}}
                    </a></p>
        <p class="lecture_time">Time from {{ $item->start_time }} to {{ $item->end_time }}</p>
            <div class="room">
                <p class="room_id">Room: {{$item->room->name}}</p>
            </div>
        </div>
        <br>
    @endif
@endforeach

@endsection
