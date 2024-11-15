@extends('layouts.layout')
<link href="{{asset('css/conference.css')}}" rel="stylesheet">

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="card">
    <p>{{$data['title']}}</p>
    @if ($data['posterUrl'])
        <img src="{{$data['posterUrl']}}" alt='Poster' />
    @endif
    <p>{{ $data['description'] }}
    <p>Conference: <a onclick="searchConferences({{ $data['conferenceId'] }})">{{ $data['conferenceName'] }}</a></p>
    <p>Start time: {{$data['startTime']}}</p>
    <p>End time: {{$data['endTime']}}</p>
    @if ($data['room'])
        <p>Room: {{ $data['room'] }}</p>
    @endif
    <p>Speaker: <a onclick="searchForPerson( {{$data['ownerId']}} )">{{ $data['ownerName'] }}</a></p>
    <p>Confirmed: {{$data['isConfirmed'] ? 'yes' : 'no'}}</p>

    <button onclick="editLecture({{$data['id']}})">Edit</button>
    @if (!$data['isConfirmed'])
        <button onclick="cancelLecture({{$data['id']}})">Cancel</button>
    @endif
</div>
@endsection

<script>

function cancelLecture(id) {
    const url = '/lectures/cancel';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ id: id }) // Send the ID as payload
    })
    .then(response => {
        if (response.ok)
            window.location = "/lectures/dashboard";
    })
    .catch(error => {
        alert(error);
    });
}

</script>