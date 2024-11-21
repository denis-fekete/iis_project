@extends('layouts.layout')

@section('content')

@php
    if($info['type'] == 'create') {
        $url = '/conferences/create';
    } else {
        $url = '/conferences/edit';
    }

@endphp

@isset($info['editingAsAdmin'])
    @if($info['editingAsAdmin'] == true)
        <div class="title_block">
        <p class="title">Editing as administrator</p>
        </div>
    @endif
@endisset


<form action="{{ url($url) }}" id="register_form" class="register_form" method="post">
    @csrf
    @isset($info)
        @isset($info['id'])
            <input class="text" type="text" name="id" id="id" value={{ $info['id'] }} hidden>
        @endisset
    @endisset

    <div class="title_block">
        <input class="title title_input" type="text" name="title" placeholder="Title"
            value="{{old('title', $conference->title)}}" required>
    </div>

    <div class="card">
        <div class="grid_horizontal">
            <label for="poster">Poster URL:</label>
            <input type="url" name="poster" id="poster"
                value="{{old('poster', $conference->poster)}}" required>
            <button type="button" onclick="refreshImg()">Refresh Image</button>
        </div>
        <img class="lecture-poster" src="" style="width=100%;" id="imgPreview">
        <br>

        <div class="horizontal_grid">
            <button disabled>Make reservation</button>
            <button disabled>Offer a lecture</button>
        </div>
        <br>

        <br><br>

        <p>Organizer:
            <a class="text_link">
                {{$user->title_before}} {{$user->name}} {{$user->surname}} {{$user->title_after}}
        </a></p>
        <label for="theme">Theme:</label>
        <select type="text" name="theme">
            @foreach ($info['themes'] as $theme)
                <option value="{{$theme->value}}"
                    @if ($theme->value == old('theme', $conference->theme))
                        selected
                    @endif
                    >{{$theme->value}}
                </option>
            @endforeach
        </select>

        <label for="description">Description:</label>
        <textarea type="textarea" name="description" id="description" required>
            {{ old('description', $conference->description) }}
        </textarea>
        <br>
        <br>

        <label for="start_time">Start time:</label>
        <input name="start_time" type="datetime-local"
            value="{{ old('start_time', $conference->start_time ? $conference->start_time->format('Y-m-d\TH:i') : '') }}"
            required>
        <br>

        <label for="end_time">End time:</label>
        <input name="end_time" type="datetime-local"
            value="{{ old('end_time', $conference->end_time ? $conference->end_time->format('Y-m-d\TH:i') : '') }}"
            required>
        <br>

        <label for="address">Address:</label>
        <input type="text" name="place_address"
            value="{{old('place_address', $conference->place_address)}}"
            required>
        <br>

        <label for="capacity" >Capacity:</label>
        <input type="number" name="capacity"
            value="{{old('capacity', $conference->capacity)}}" required>
        <br>

        <label for="price">Price per person:
            <input type="number" name="price"
                value="{{old('price', $conference->price)}}" required>
        Kč</label>
        <br>

        <label for="bank_account">Your banc account number:
            <input type="text" name="bank_account"
                value="{{old('bank_account', $conference->bank_account)}}" required />
        <br>

        <br>
        @isset($info['type'])
            @if ($info['type'] == 'create')
                <button type"submit">Create new</button>
            @else
                <button type"submit">Save changes</button>
            @endif
        @endisset

    </div>
</form>
        @isset($info['type'])
            @if ($info['type'] == 'edit')
                <br>
                <button class="delete_btn" onclick="navigateTo('/conferences/delete/{{$conference->id}}')">Delete</button>
            @endif
        @endisset

<script>
    // disable submit on Enter to allow new lines
    document.getElementById('description').addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
        }
    });

    function refreshImg() {
        const posterURL = document.getElementById('poster').value;
        const img = document.getElementById('imgPreview');
        img.src = posterURL;

    }

</script>

@endsection
