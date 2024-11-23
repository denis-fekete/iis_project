@extends('layouts.layout')

@section('content')

<script>
    function applyFilters() {
        const direction = document.getElementById('direction_selector').value;
        const search = document.getElementById('searchString').value;

        navigateTo("/admin/users/search?" +
            "order=" + direction + "&" +
            "search=" + search
            );
    }

    function setUserRole(id) {
        const role = document.getElementById('user' + id).value;
        navigateTo("/admin/users/setRole/?id=" + id + "&role=" + role);
    }

    function showConfirm(id) {
        document.getElementById('form' + id).style.display = "flex";
        document.getElementById('warn' + id).style.display = "none";
    }

    function hideConfirm(id) {
        document.getElementById('form' + id).style.display = "none";
        document.getElementById('warn' + id).style.display = "block";
    }

</script>

<div class="title_block">
    <p class="title">User search</h1>
</div>

<div class="div_center">
<div class="filter">
    <label for="search">Search for:</label>
    <input class="search" style="width: 100%;" type="text" name="search" id="searchString"
        @isset($info['default_search'])
            value="{{$info['default_search']}}"
        @endisset
        >
    <br><br>

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

<div class="grid_vertical_4_collumns">
@foreach ($users as $user)
<div class="card">
    <p>{{$user->title_before}} {{$user->name}} {{$user->surname}} {{$user->title_after}}</p>
    <div class="grid_horizontal">
        <button onclick="navigateTo('/users/search/{{ $user->id }}')">View</button>
        <form action="{{ url('/admin/users/setRole') }}" method="post">
            @csrf
            <input type="text" name="user_id" value="{{$user->id}}" hidden>
            <select name="role">
                @foreach ($info['roles'] as $role)
                    <option value="{{$role->value}}"
                        @if ($role->value == $user->role)
                            selected
                        @endif
                        >{{$role->name}}
                    </option>
                @endforeach
            </select>
            <input type="submit" value="Set role">
        </form>
    </div>
    <div class="grid_horizontal">
        <div class="grid_horizontal" id="warn{{ $user->id }}">
            <button class="delete_btn" onclick="showConfirm('{{ $user->id }}')" id="warn{{ $user->id }}">Delete</button>
        </div>

        <div class="grid_horizontal" id="form{{ $user->id }}" style="display: none">
            <button onclick="hideConfirm('{{ $user->id }}')">Cancel</button>

            <form action="{{ url('/users/delete?force=false') }}" method="post" >
                @csrf
                <input type="text" name="user_id" value="{{$user->id}}" hidden>
                <input type="submit" class="delete_btn" value="Safe delete">
            </form>
            <form action="{{ url('/users/delete?force=true') }}" method="post" >
                @csrf
                <input type="text" name="user_id" value="{{$user->id}}" hidden>
                <input type="submit" class="delete_btn" value="Force delete">
            </form>
        </div>

    </div>
</div>

@endforeach
</div>

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

    .grid_vertical_4_collumns {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-inline: 5%;
        margin-top: 1em;
        margin-bottom: 1em;
    }
</style>

@endsection
