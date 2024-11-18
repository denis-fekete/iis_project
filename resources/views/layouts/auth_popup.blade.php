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


<style>
.register_form {
    margin-top: 5px;
}

.form_label {
    display: inline-block;
    width: 120px;
    text-align: center;
    margin-bottom: 10px;
}

.form_input {
    display: inline-block;
    text-align: left;
}

.background_blur {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.auth_window {
    background: white;
    padding: 20px;
    max-width: 400px;
    margin: 10% auto;
    border-radius: 8px;
    text-align: center;
}

</style>
