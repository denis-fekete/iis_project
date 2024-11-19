@extends('layouts.layout')

@section('content')
<button onclick="navigateTo('/conferences/create')">Create new conference</button>
@foreach ($conferences as $item)
    <div class="card">
        <p class="card_title">{{$item->title}}</p>
        <button onclick="navigateTo( '/conferences/edit/{{ $item->id }}' )"                     >Edit</button>
        <button onclick="navigateTo( '/conferences/conference/{{ $item->id }})"                 >Preview</button>
        <button onclick="navigateTo( '/conferences/conference/lectures/{{ $item->id }}')"       >Lectures</button>
        <button onclick="navigateTo( '/conferences/conference/reservations/{{ $item->id }}')    ">Reservations</button>
    </div>
@endforeach
@endsection
