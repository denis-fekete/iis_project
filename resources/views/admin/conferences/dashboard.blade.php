@extends('layouts.layout')

@section('content')
<h2>Admin Conferences dashboard</h2>
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
    <div>
        <div class="card">
            <p class="card_title">{{$item->title}}</p>
            <p class="card_theme">Themes: {{$item->theme}}</p>
            <button onclick="navigateTo( '/conferences/conference/{{ $item->id }}' )"                   >Preview</button>
            <button onclick="navigateTo( '/admin/conferences/edit/{{ $item->id }}' )"                   >Edit</button>
            <button onclick="navigateTo( '/admin/conferences/conference/lectures/{{ $item->id }}')"     >Lectures</button>
            <button onclick="navigateTo( '/admin/conferences/conference/reservations/{{ $item->id }}')" >Reservations</button>
        </div>
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

<style>
.all_cards {
    display: grid;
    gap: 10px;
    margin-inline: 5%;
    margin-top: 1%;
    margin-bottom: 1%;
}

.card {
    background-color: wheat;
    margin-inline: 5%;
    padding-inline: 5%;
    padding-bottom: 1%;
}

.radio_group {
    grid-template-columns: repeat(2, 1fr);
}

.filters {
    margin-inline: 4%;
    background-color: wheat;
}


.card_title{
    font-size: 20px;
    color:red;
}

.card_theme {
    font-size: 12px;
    color:gray
}

</style>
