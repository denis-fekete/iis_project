@extends('layouts.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')
<button onclick="createConference()">Create new conference</button>
@foreach ($conferences as $item)
    <div class="card">
        <p class="card_title">{{$item->title}}</p>
        <button onclick="editConference({{ $item->id }})" >Edit</button>
        <button onclick="searchConferences({{ $item->id }})" >Preview</button>
        <button onclick="editConferenceLectures({{ $item->id }})">Lectures</button>
        <button onclick="editConferenceReservations({{ $item->id }})">Reservations</button>
    </div>
@endforeach
@endsection
