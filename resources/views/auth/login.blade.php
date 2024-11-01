@extends('layout.layout')

@section('content')
<link href="/css/registration.css" rel="stylesheet">
<script src="/httpHandler.js"></script>

<div>
    <form action="{{ url('login') }}" id="register_form" class="register_form" method="post">
        @csrf
        <label class="form_label" for="email">Email:</label>
        <input class="form_input" type="email" name="email" id="email"required>
        <br>
        <label class="form_label" for="password">Password:</label>
        <input class="form_input" type="password" name="password" id="password"required>
        <button type"submit">Login</button>
        <br>
    </form>
</div>
@endsection

