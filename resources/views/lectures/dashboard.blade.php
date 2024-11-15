@extends('layouts.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')
<h4>My lectures</h4>
@foreach ($cards as $item)
    <div class="card" onclick="previewLecture({{$item['id']}})">
        <p>{{$item['title']}}</p>
        <p>Confrence: {{ $item['conferenceTitle'] }}</p>
        @if ( $item['isConfirmed'] )
            <p>Confirmed: Yes</p>
        @else
            <p>Confirmed: No</p>
        @endif
    </div>
@endforeach
@endsection
