@extends('layout.layout')
<link href="/css/search_cards.css" rel="stylesheet">


@section('content')
<div class="card">
    <p class="card_name">{{$name}}</p>
    <p class="card_description">{{$description}}</p>
    <p class="card_organizer_name">{{$organizerName}}</p>
    <p class="card_tags">{{$tags}}</p>
    <p class="card_price">{{$price}}Kč/€/$</p>
</div>
@endsection

