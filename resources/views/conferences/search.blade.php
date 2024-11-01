@extends('layout.layout')
<link href="{{asset('css/search_cards.css')}}" rel="stylesheet">

@section('content')
@foreach ($cards as $item)
    <div onclick="searchConferences({{ $item->id }})" class="card">
        <p class="card_title">{{$item->title}}</p>
        <p class="card_description">{{$item->description}}</p>
        <p class="card_theme">{{$item->theme}}</p>
        <p class="card_price">{{$item->price}}Kč</p>
    </div>
@endforeach
@endsection

