@extends('layouts.layout')

@section('content')

<div class="title_block">
    <p class="title">Search</h1>
</div>

<div class="div_center">
<div class="filter">
    <label for="search">Search for title:</label>
    <input class="search" style="width: 100%;" type="text" name="search" id="searchString"
        @isset($info['default_search'])
            value="{{$info['default_search']}}"
        @endisset
        >
    <br><br>

    <label for="theme">Theme:</label>
    <select type="text" name="themes" id="theme_selector">
        <option value=""
            @if ($info["default_theme"] == "")
                selected
            @endif
            >All
        </option>
        @foreach ($info['themes'] as $theme)
            <option value="{{$theme->value}}"
                @if ($theme->value == old('themes', $info["default_theme"]))
                    selected
                @endif
                >{{$theme->value}}
            </option>
        @endforeach
    </select>

    <label for="orders">Order by:</label>
    <select type="text" name="orders" id="order_selector">
        @foreach ($info['orders'] as $order)
            <option value="{{$order->value}}"
                @if ($order->value == old('order', $info["default_order"]))
                    selected
                @endif
                >{{$order->value}}
            </option>
        @endforeach
    </select>

    <label for="directions">Direction:</label>
    <select type="text" name="directions" id="direction_selector">
        @foreach ($info['directions'] as $order)
            <option value="{{$order->value}}"
                @if ($order->value == old('directions', $info["default_directions"]))
                    selected
                @endif
                >
                @if($order->value == "asc")
                    Ascending
                @else
                    Descending
                @endif
            </option>
        @endforeach
    </select>
    <br><br>
    <div class="div_center">
        <input type="submit" style="width: 20%;" onclick="applyFilters()" value="Apply">
    </div>
</div>
</div>

<div class="grid_vertical_2_collumns">
@foreach ($conferences as $item)
    @if(isset($info['role']) && $info['role'] == 'admin')
        <div class="card">
            <p class="card_title">{{$item->title}}</p>
            @if ($item->poster)
                <img class="card_image" src="{{$item->poster}}" alt="Placeholder image">
            @endif
            <p class="card_description">{{$item->description}}</p>
            <p class="text_theme">{{$item->theme}}</p>
            <p class="card_price">Price: {{$item->price}}Kč</p>
            <br>
            <button onclick="navigateTo( '/admin/conferences/edit/{{ $item->id }}' )"                   >Edit</button>
            <button onclick="navigateTo( '/admin/conferences/conference/lectures/{{ $item->id }}')"     >Lectures</button>
            <button onclick="navigateTo( '/admin/conferences/conference/reservations/{{ $item->id }}')" >Reservations</button>
            <button onclick="navigateTo( '/admin/conferences/conference/rooms/{{ $item->id }}')"        >Rooms</button>
            <button onclick="navigateTo('/conferences/conference/{{ $item->id }}')"                     >Details</button>

        </div>
    @else
        <div class="card" onclick="navigateTo('/conferences/conference/{{ $item->id }}')">
            <p class="title">{{$item->title}}</p>
            @if ($item->title)
                <img class="card_image" src="{{$item->poster}}" alt="Placeholder image">
            @endif
            <p>{{$item->description}}</p>
            <p class="text_theme">{{$item->theme}}</p>
            <p class="card_price">Price: {{$item->price}}Kč</p>
        </div>
    @endisset

@endforeach
</div>

<script>
    function applyFilters() {
        const theme = document.getElementById('theme_selector').value;
        const order = document.getElementById('order_selector').value;
        const direction = document.getElementById('direction_selector').value;
        const search = document.getElementById('searchString').value;

        navigateTo("/conferences/search?" +
            "themes=" + theme + "&" +
            "order=" + order + "&" +
            "directions=" + direction + "&" +
            "search=" + search
            );
    }
</script>

<style>
    .radio_box {
        border: none;
        margin-bottom: 0.3em;
    }

    .radio_group {
        grid-template-columns: repeat(2, 1fr);
    }

    .card_image {
        display: block;
        margin: 0 auto 20px auto;
        border-radius: 10px;
        max-width: 100%;
        max-height: 300px;
        object-fit: cover;
    }
</style>

@endsection
