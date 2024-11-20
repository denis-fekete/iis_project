@extends('layouts.layout')

@section('content')
<div class="title_block">
    <p class="title">Profile</p>
</div>

@if(isset($info['myself']) && $info['myself'] == true)
    <div class="card" id="profile_info">
        <p>Email: {{$person->email}} </p>
        <p>Fullname: {{$person->title_before}} {{$person->name}} {{$person->surname}} {{$person->title_after}} </p>
        <p>Description: {{$person->description}}</p>
        <br>
        <button onclick="enterEdit()">Edit</button>
    </div>

    <div class="card" id="profile_edit">
        <form action="{{ url('/users/profile/edit') }}" id="register_form" method="post">
            @csrf
            <input type="text" name="user_id" value="{{$person->id}}" hidden required>
            <label for="email">Email:</label>
            <input type="email" name="email" value="{{$person->email}}" disabled>
            <br>
            <label for="title_before">Title before name:</label>
            <input type="text" name="title_before" value="{{$person->title_before}}">
            <br>
            <label for="name">Name:</label>
            <input type="text" name="name" value="{{$person->name}}">
            <br>
            <label for="surname">Surname:</label>
            <input type="text" name="surname" value="{{$person->surname}}">
            <br>
            <label for="title_after">Title after name:</label>
            <input type="text" name="title_after" value="{{$person->title_after}}">
            <br><br>
            <label for="description">Description:</label>
            <input type="text" name="description" value="{{$person->description}}">
            <br><br>
            <label for="new_password">New password:</label>
            <input type="password" name="new_password" value="">
            <br><br>
            <label for="password">Confirm password:</label>
            <input type="password" name="password" value="" required>
            <br>
            <input type="submit" value="Submit">
        </form>
        <button onclick="exitEdit()">Exit editing</button>
    </div>
@else
    <div class="card">
        <p>Email: {{$person->email}} </p>
        <p>Fullname: {{$person->title_before}} {{$person->name}} {{$person->surname}} {{$person->title_after}} </p>
        <p>Description: {{$person->description}}</p>
    </div>
@endif

<br>
<div class="title_block">
    <p class="title">Future lectures</p>
</div>
@foreach ($futureLectures as $item)
    <div class="card">
        <p>Title: {{$item->title}} </p>
        <p>Time: {{$item->start_time}} </p>
        <p>Conference: <a class="text_link" onclick="navigateTo('/conferences/conference/{{$item->conference->id}}')">{{$item->conference->title}}</a> </p>
    </div>
    <br>
@endforeach

<br>
<div class="title_block">
    <p class="title">Old lectures</p>
</div>
@foreach ($pastLectures as $item)
    <div class="card">
        <p>Title: {{$item->title}} </p>
        <p>Time: {{$item->start_time}} </p>
        <p>Conference: <a class="text_link" onclick="navigateTo('/conferences/conference/{{$item->conference->id}}')">{{$item->conference->title}}</a> </p>
    </div>
    <br>
@endforeach


<style>
    #profile_info {
        display: block;
    }

    #profile_edit {
        display: none;
    }
</style>

<script>
    function enterEdit() {
        document.getElementById('profile_info').style.display = 'none';
        document.getElementById('profile_edit').style.display = 'block';
    }

    function exitEdit() {
        document.getElementById('profile_info').style.display = 'block';
        document.getElementById('profile_edit').style.display = 'none';
    }
</script>

@if(session('info'))
    @if(isset(session('info')['editing']) && session('info')['editing'] == true)
        <script>
            // when document is fully loaded call enterEdit() if we want to still edit
            document.addEventListener('DOMContentLoaded', function() {
                enterEdit();
            });
        </script>
    @endif
@endif

@endsection
