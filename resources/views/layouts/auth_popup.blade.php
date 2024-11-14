<link href="/css/registration.css" rel="stylesheet">

@php
    $currentUrl = url()->current();
@endphp

<div id="loginPopup" class="background_blur" style="display: none;">
    <div class="auth_window">
        <div>
            <h2>Login</h2>
            <form action="{{ url('auth/login') }}" id="register_form" class="register_form" method="post">
                @csrf
                <input name="return_to" value='{{$currentUrl}}' required hidden>

                <label class="form_label" for="email">Email:</label>
                <input class="form_input" type="email" name="email" id="email"required>
                <br>
                <label class="form_label" for="password">Password:</label>
                <input class="form_input" type="password" name="password" id="password"required>
                <br>
                <button type"submit">Login</button>
            </form>
        </div>
        <br>
        <div>
            <h2>Register</h2>
            <form action="{{ url('auth/register') }}" id="register_form" class="register_form" method="post">
                @csrf
                <input name="return_to" value='{{$currentUrl}}' required hidden>

                <label class="form_label" for="email">Email:</label>
                <input class="form_input" type="email" name="email" id="email"required>
                <br>
                <label class="form_label" for="name">Name:</label>
                <input class="form_input" type="text" name="name" id="name"required>
                <br>
                <label class="form_label" for="surname">Surname:</label>
                <input class="form_input" type="text" name="surname" id="surname"required>
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
    </div>
</div>

<script>
    function showLogin() {
        document.getElementById('loginPopup').style.display = 'block';
    }

    function hideLogin() {
        document.getElementById('loginPopup').style.display = 'none';
    }

    document.getElementById('loginPopup').addEventListener('click', function(event) {
        if (event.target === this) {
            hideLogin();
        }
    });
</script>
