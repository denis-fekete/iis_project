
<div id="loginPopup" class="background_blur" style="display: none;">
    <div class="auth_window">
        @include('layouts.auth_form')
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
    background: #E1DDA8;
    padding: 20px;
    max-width: 400px;
    margin: 10% auto;
    border-radius: 8px;
    text-align: center;
    box-shadow: 2px 2px 1px #3E3C3E;
}

</style>
