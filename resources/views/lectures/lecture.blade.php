@extends('layouts.layout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="app-base-url" content="{{ env('APP_BASE_URL') }}">
<div class="card">
    <h1 class="lecture-title">{{$data['title']}}</h1>
    <ph>
    @if ($data['posterUrl'])
        <img src="{{$data['posterUrl']}}" alt='Poster' class="lecture-poster"/>
    @endif
    <div class="lecture-description">
        <p><h3>Description:</h3></p>
        <p>{{ $data['description'] }}</p>
    </div>
    <div class="lecture-attributes">


        <p>Conference: <a class="text_link"  href="{{ config('app.url') }}/conferences/conference/{{$data['conferenceId']}}">{{ $data['conferenceName'] }}</a></p>
        @if ($data['startTime'] && $data['endTime'])
            <p>Start time: {{$data['startTime']}}</p>
            <p>End time: {{$data['endTime']}}</p>
        @endif
        @if ($data['room'])
            <p>Room: {{ $data['room'] }}</p>
        @endif
        <p>Speaker: <a class="text_link"  href="{{ config('app.url') }}/users/search/{{$data['ownerId']}}">{{ $data['ownerName'] }}</a></p>
        <p>Confirmed: {{$data['isConfirmed'] ? 'yes' : 'no'}}</p>
    </div>

    @if (!$data['canEdit'])
        <div class="lecture-control">
            <button onclick="navigateTo( '/lectures/edit/{{ $data['id'] }}' )">Edit</button>
            @if (!$data['isConfirmed'])
                <button onclick="cancelLecture({{$data['id']}})">Cancel</button>
            @endif
        </div>
    @endif
</div>
@endsection

<script>

function cancelLecture(id) {
    const baseUrl = document.querySelector('meta[name="app-base-url"]').getAttribute('content');
    const url = `${baseUrl}/lectures/cancel`;
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
