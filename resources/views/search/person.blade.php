@extends('layouts.layout')
<link href="{{asset('css/person.css')}}" rel="stylesheet">

@section('content')
<div class="person">
    <p>Name: {{$person->name}} </p>
    <p>Surname: {{$person->surname}} </p>
    <p>Email: {{$person->email}} </p>
</div>
@endsection

