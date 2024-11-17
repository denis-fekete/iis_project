@extends('layouts.layout')

@section('content')
<h4>My lectures</h4>
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
@endsection
