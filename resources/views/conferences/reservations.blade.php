@extends('layouts.layout')

@section('content')

@php
    $url = 'conferences/conference/confirmReservation';
@endphp

@isset($info['editingAsAdmin'])
    @if($info['editingAsAdmin'] == true)
        <div class="title_block">
        <p class="title">Editing as administrator</p>
        </div>
    @endif
@endisset


<div class="title_block">
    <p class="title">Conference reservations</p>
</div>

<div class="card">
    <p class="title_2">Seats count: {{$data['seatsCount']}}</p>
    <p class="title_2">Reserved seats count: {{$data['reservationsCount']}}</p>
    <p class="title_2">Confirmed seates count: {{$data['confirmedCount']}}</p>
    <p class="title_2">Free seats count: {{$data['freeSeats']}}</p>
</div>
<br>

    @foreach ($data['reservations'] as $reservation)
        <p>Name: <a href="/users/search/{{$reservation['userId']}}">{{$reservation['userName']}}</a></p>
        <p>Count: {{$reservation['count']}}</p>
        @if ($reservation['confirmed'])
            <p>Reservation was confirmed.</p>
        @else
            <p>Reservation has not been confirmed yet!</p>
            <p>To be paid: {{$reservation['sum']}}</p>
            <p class="text_bold">Reservation has not been confirmed yet!</p>
            <p>Variable symbol: {{$data['conferenceId']}}{{$reservation['reservationId']}}</p>
            <button onClick="warning({{$reservation['reservationId']}}, {{$reservation['reservationId']}})">Confirm payment</button>
        @endif
    </div>
    <br>
@endforeach


<div id="warning" class="popup" style="display: none;">
    <div class="card popup-content">
        <p>Are you sure that reservation was paid?</p>
        <div>
            <form action="{{url ($url) }}" method="post">
                @csrf
                <input type="hidden" name="conferenceId" value="{{$data['conferenceId']}}"/>
                <input id="resIdInput" type="hidden" name="reservationId" value=""/>
                <button id="confirmCancel">Confirm</button>
            </form>
            <button onclick="closeWarning()">Cancel</button>
        </div>
    </div>
</div>
@endsection

<script>
    function warning(reservationId) {
        document.getElementById('resIdInput').value = reservationId;
        const popup = document.getElementById('warning');
        popup.style.display = 'block';
    }

    function closeWarning() {
        const popup = document.getElementById('warning').style.display = 'none';
    };

</script>
