@extends('layouts.layout')
<link href="{{asset('css/search_cards.css')}}" rel="stylesheet">

@section('content')

<div class="filters">
    <fieldset class="radio_box">
        @foreach ($info['themes'] as $item)
            <input type="radio" name="themes" value="{{$item->value}}"
                @if ($item->value == $info['default_theme'])
                    checked
                @endif
            >
            <label>{{$item->value}}</label>
        @endforeach
    </fieldset>
    <fieldset class="radio_box">
        @foreach ($info['orders'] as $item)
            <input type="radio" name="orders" value="{{$item->value}}"
                @if ($item->value == $info['default_orders'])
                    checked
                @endif
            >
            <label>{{$item->value}}</label>
        @endforeach
    </fieldset>
    <fieldset class="radio_box">
        <input type="radio" name="directions" value="asc"
            @if ("asc" == $info['default_directions'])
                checked
            @endif
            >
        <label>Ascending</label>
        <input type="radio" name="directions" value="desc"
            @if ("desc" == $info['default_directions'])
                checked
            @endif
            >
        <label>Descending</label>
    </fieldset>

    <input type="submit" onclick="applyFilters()" value="Apply">
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

<script>
    function applyFilters() {
        const theme = document.querySelector('input[name="themes"]:checked')?.value;
        const order = document.querySelector('input[name="orders"]:checked')?.value;
        const direction = document.querySelector('input[name="directions"]:checked')?.value;

         window.location = "/conferences/search/" +
            theme + ";" +
            order + ";" +
            direction;
    }
</script>
