@extends('layouts.layout')

@section('content')

<div class="title_block">
    <p class="title">User search</h1>
</div>

<div class="card">
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

<div class="grid_vertical">
@foreach ($users as $item)

<div class="card">
    <p>{{$item->title_before}} {{$item->name}} {{$item->surname}} {{$item->title_after}}</p>
    <br>
    <div class="grid_horizontal">
        <button onclick="navigateTo('/users/search/{{ $item->id }}')">View</button>
        <button onclick="navigateTo('/users/delete/{{ $item->id }}')">Delete</button>
    </div>
</div>

@endforeach
</div>

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

<style>
    .radio_box {
        border: none;
        margin-bottom: 0.3em;
    }

    .radio_group {
        grid-template-columns: repeat(2, 1fr);
    }

    .card_image {
        max-width: 100%;
    }
</style>

@endsection
