@extends('layout.layout')

@section('content')
<link href="/css/registration.css" rel="stylesheet">
<script src="/httpHandler.js"></script>

<div>

    @if ($errors->any())
        <div style="background-color: red">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>

    @endif

    <form action="{{ url('register') }}" id="register_form" class="register_form" method="post">
        @csrf
        <label class="form_label" for="email">Email:</label>
        <input class="form_input" type="email" name="email" id="email"required>
        <br>
        <label class="form_label" for="name">Name:</label>
        <input class="form_input" type="text" name="name" id="name"required>
        <br>
        <label class="form_label" for="password">Password:</label>
        <input class="form_input" type="password" name="password" id="password"required>
        <br>
        <label class="form_label" for="password_confirmation">Confirm password :</label>
        <input class="form_input" type="password" name="password_confirmation" id="password_confirmation"required>
        <br>
        <button type"submit">Register</button>
        <br>
    </form>
</div>
@endsection

