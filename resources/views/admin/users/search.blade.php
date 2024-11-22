@extends('layouts.layout')

@section('content')

<script>
    function applyFilters() {
        const direction = document.querySelector('input[name="directions"]:checked')?.value;
        let search = document.getElementById('searchString').value;

        navigateTo("/admin/users/search?" +
            "orderDir=" + direction + "&" +
            "searchFor=" + search
            );
    }

    function setUserRole(id) {
        const role = document.getElementById('user' + id).value;
        navigateTo("/admin/users/setRole/?id=" + id + "&role=" + role);
    }
</script>

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
    <input type="text" name="search" id="searchString"
        @isset($info['default_search'])
            value="{{$info['default_search']}}"
        @endisset
        >
    <br>
    <input type="submit" onclick="applyFilters()" value="Apply">
</div>

<div class="grid_vertical_4_collumns">
@foreach ($users as $user)
<div class="card">
    <p>{{$user->title_before}} {{$user->name}} {{$user->surname}} {{$user->title_after}}</p>
    <div class="grid_horizontal">
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
        <button onclick="navigateTo('/users/search/{{ $user->id }}')">View</button>

        <form action="{{ url('/users/delete') }}" method="post">
            @csrf
            <input type="text" name="user_id" value="{{$user->id}}" hidden>
            <input type="submit" class="delete_btn" value="Delete">
        </form>
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
