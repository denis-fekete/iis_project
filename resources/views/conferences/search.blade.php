@extends('layouts.layout')
<link href="{{asset('css/search_cards.css')}}" rel="stylesheet">

@section('content')

<div class="filters">
    <p>filters</p>
</div>

<div class="all_cards">
@foreach ($conferences as $item)
    <div class="card" onclick="searchConferences({{ $item->id }})">
        <p class="card_title">{{$item->title}}</p>
        <img class="card_image" src="https://picsum.photos/seed/{{$item->title}}/600/150" alt="Placeholder image">
        <p class="card_description">{{$item->description}}</p>
        <p class="card_theme">Themes: {{$item->theme}}</p>
        <p class="card_price">Price: {{$item->price}}Kč</p>
    </div>
@endforeach
</div>
@endsection
