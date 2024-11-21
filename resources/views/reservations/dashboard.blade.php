@extends('layouts.layout')

@section('content')

<div class="title_block">
    <p class="title">My reservations</h1>
</div>

<div class="grid_vertical">
@foreach ($reservations as $reservation)
    <div class="card">
        <p class="title_2" >Conference: <a href="/conferences/conference/{{ $reservation['conferenceId']}}">{{ $reservation['conferenceName']}}</a></p>
        <p>From: {{ $reservation['startTime'] }}</p>
        <p>To: {{ $reservation['endTime'] }}</p>
        <p>Number of people: {{ $reservation['peopleCount'] }}</p>
        @if ($reservation['confirmed'])
            <p>Reservation was paid and confirmed!</p>
        @else
            <p>You need to pay <b>{{ $reservation['toPay'] }}!</b></p>
            <p>Credentials to pay: <b>{{ $reservation['bankAccount'] }}</b></p>
            <p>Variable symbol: {{$reservation['conferenceId']}}{{$reservation['reservationId']}}</p>
        @endif
        <br>
        <div class="grid_horizontal">
        <button onclick="warning('{{ $reservation['reservationId'] }}')">Cancel reservation</button>
        <button onclick="navigateTo('/reservations/schedule/{{ $reservation['reservationId'] }}')">Show schedule</button>
        </div>
    </div>
@endforeach

<div id="warning" class="popup" style="display: none;">
    <div class="card popup-content">
        <p>If you will cancel reservation, money will not be returned!</p>
        <div>
            <button id="confirmCancel">Cancel anyway</button>
            <button onclick="closeWarning()">Close warning</button>
        </div>
    </div>
</div>

@endsection

<script>
    function warning(reservationId) {
        const popup = document.getElementById('warning');
        popup.style.display = 'block';
    
        const confirmButton = document.getElementById('confirmCancel');
        confirmButton.onclick = function () {
            window.location.href = `/reservations/cancel/${reservationId}`;
        };
    }

    function closeWarning() {
        const popup = document.getElementById('warning').style.display = 'none';
    };

</script>