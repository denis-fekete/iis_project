@extends('layout.layout')
<link href="{{asset('css/person.css')}}" rel="stylesheet">

@section('content')
<div class="person">
    <p>Name: {{$name}} </p>
</div>
@endsection

