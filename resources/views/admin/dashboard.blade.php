@extends('layouts.layout')

@section('content')
<div>
    <div class="navigation">
        <div class="title_block">
            <p class="title">Admin dashboard</p>
        </div>
        <button class="navigation_button" onclick="navigateTo('/admin/rooms/dashboard')">Rooms</button>
        <button class="navigation_button" onclick="navigateTo('/admin/conferences/search')">Conferences</button>
    </div>
</div>
@endsection

