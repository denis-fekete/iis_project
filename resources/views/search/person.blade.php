@extends('layouts.layout')

@section('content')
<div class="person">
    <p>Name: {{$person->name}} </p>
    <p>Surname: {{$person->surname}} </p>
    <p>Email: {{$person->email}} </p>
</div>
@endsection

