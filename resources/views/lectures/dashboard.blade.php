@extends('layouts.layout')

@section('content')
<div class="title_block">
    <p class="title">My lectures</h1>
</div>

<div class="grid_vertical">
@foreach ($cards as $item)
    <div class="card" onclick="navigateTo( '/lectures/lectures/{{ $item['id'] }}' )">
        <p>{{$item['title']}}</p>
        <p>Confrence: {{ $item['conferenceTitle'] }}</p>
        @if ( $item['isConfirmed'] )
            <p>Confirmed: Yes</p>
        @else
            <p>Confirmed: No</p>
        @endif
    </div>
@endforeach
</div>
@endsection
