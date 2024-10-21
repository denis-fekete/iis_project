@extends('layout.layout')
<link href="/css/search_cards.css" rel="stylesheet">


@section('content')
@foreach ($cards as $item)
    <div onclick="searchForCard({{ $item->id }})" class="card">
        <p class="card_name">{{$item->name}}</p>
        <p class="card_description">{{$item->description}}</p>
        <p class="card_organizer_name">{{$item->organizerName}}</p>
        <p class="card_tags">{{$item->tags}}</p>
        <p class="card_price">{{$item->price}}Kč/€/$</p>
    </div>
@endforeach
@endsection

