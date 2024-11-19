@extends('layouts.layout')

@section('content')
<div>
    <h1>Admin dashboard</h1>

    <button onclick="navigateTo('/admin/rooms/dashboard')">Rooms</button>
    <button onclick="navigateTo('/admin/conferences/search')">Conferences</button>
</div>
@endsection

