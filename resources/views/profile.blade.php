@extends('layouts.layout')

@section('content')
<div>
    <div class="title_block">
        <p class="title">My profile</h1>
    </div>
    <h2>Welcome {{$user->name}}</h2>
</div>
@endsection

