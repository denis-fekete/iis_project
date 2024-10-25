@extends('layout.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')
<button onclick="createConference()">Create new conference</button>
@foreach ($cards as $item)
    <div class="card">
        <p class="card_title">{{$item->title}}</p>
        <button>Edit</button>
        <button onclick="searchConferences({{ $item->id }})" >Preview</button>
        <button onclick="editConferenceLectures({{ $item->id }})">Lectures</button>
    </div>
@endforeach
@endsection
