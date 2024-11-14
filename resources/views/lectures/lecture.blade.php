@extends('layouts.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')
<div class="card">
    <p>{{$data['title']}}</p>
    @if ($data['posterUrl'])
        <img src="{{$data['posterUrl']}} alt="Poster" />
    @endif
    <p>Conference: <a onclick="searchConferences({{ $data['conferenceId'] }})">{{ $data['conferenceName'] }}</a></p>
    <p>Start time: {{$data['startTime']}}</p>
    <p>End time: {{$data['endTime']}}</p>
    @if ($data['room'])
        <p>Room: {{ $data['room'] }}</p>
    @endif
    <p>Speaker: <a onclick="searchForPerson( {{$data['ownerId']}} )">{{ $data['ownerName'] }}</a></p>
    <p>Confirmed: {{$data['isConfirmed'] ? 'yes' : 'no'}}</p>
</div>
@endsection

