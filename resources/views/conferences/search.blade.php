@extends('layouts.layout')

@section('content')

<div class="title_block">
    <p class="title">Search</h1>
</div>

<div class="card">
    <fieldset class="radio_box">
        <input type="radio" name="themes" value=""
                @if ($info['default_theme'] == "")
                    checked
                @endif
        >
        <label>All</label>
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
    <input type="text" name="search" id="searchString"
        @isset($info['default_search'])
            value="{{$info['default_search']}}"
        @endisset
        >
    <br>
    <input type="submit" onclick="applyFilters()" value="Apply">
</div>

<div class="grid_vertical_2_collumns">
@foreach ($conferences as $item)
    @if(isset($info['role']) && $info['role'] == 'admin')
        <div class="card">
            <p class="card_title">{{$item->title}}</p>
            <img class="card_image" src="https://picsum.photos/seed/{{$item->title}}/1000/200" alt="Placeholder image">
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
            <img class="card_image" src="https://picsum.photos/seed/{{$item->title}}/600/150" alt="Placeholder image">
            <p>{{$item->description}}</p>
            <p class="text_theme">{{$item->theme}}</p>
            <p class="card_price">Price: {{$item->price}}Kč</p>
        </div>
    @endisset

@endforeach
</div>

<script>
    function applyFilters() {
        const theme = document.querySelector('input[name="themes"]:checked')?.value;
        const order = document.querySelector('input[name="orders"]:checked')?.value;
        const direction = document.querySelector('input[name="directions"]:checked')?.value;
        let search = document.getElementById('searchString').value;

        navigateTo("/conferences/search?" +
            "themes=" + theme + "&" +
            "orderBy=" + order + "&" +
            "orderDir=" + direction + "&" +
            "searchFor=" + search
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
        width: 100%;
    }
</style>

@endsection
