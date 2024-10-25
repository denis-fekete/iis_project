@extends('layout.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')
<h4>My lectures</h4>
<button onclick="createLectures()">Create new lecture</button>
@foreach ($cards as $item)
    <div class="card">
        <p class="card_title">{{$item->title}}</p>
        @if ( $item->is_confirmed )
            <p>Confirmed</p>
        @else
            <p>Not confirmed</p>
        @endif
        <button onclick="editLecture({{$item->id}})">Edit</button>
        <button>Preview</button>
        <button>Cancel</button>
    </div>
@endforeach
@endsection
