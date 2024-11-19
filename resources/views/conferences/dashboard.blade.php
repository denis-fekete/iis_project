@extends('layouts.layout')

@section('content')

<div class="title_block">
    <p class="title">My conferences</h1>
</div>

<button onclick="navigateTo('/conferences/create')">Create new conference</button>

<div class="grid_vertical">
@foreach ($conferences as $item)
    <div class="card">
        <p class="card_title">{{$item->title}}</p>
        <div class="grid_horizontal">
            <button onclick="navigateTo( '/conferences/edit/{{ $item->id }}' )"                     >Edit</button>
            <button onclick="navigateTo( '/conferences/conference/{{ $item->id }}')"                 >Preview</button>
            <button onclick="navigateTo( '/conferences/conference/lectures/{{ $item->id }}')"       >Lectures</button>
            <button onclick="navigateTo( '/conferences/conference/reservations/{{ $item->id }}')    ">Reservations</button>
        </div>
    </div>
@endforeach
</div>
@endsection
