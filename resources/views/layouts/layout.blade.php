<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @include('layouts.common_styles')

    <div class="body_container">
        <header>
            @include('layouts.user_navigation')
        </header>

        <main>
            @include('layouts.notification')

            @include('layouts.auth_popup')

            {{-- add specific contents of the page --}}
            <div class="contents" id="contents">
                @yield('content')
            </div>
        </main>

        <footer>
            <div class="page_footer" id="page_footer">
                <p>License</p>
            </div>
        </footer>
    </div>  {{-- body_container --}}
</body>
</html>

{{-- if user was redirected with open_login, open login outright --}}
@isset($info['open_login'])
    <script>
        showLogin();
    </script>
@endisset

{{-- open notifications if set --}}
@isset($notification)
    <script>
        showNotification();
    </script>
@endisset

<script>
    window.appBaseUrl = "{{ config('app.url') }}";
    function navigateTo(url) {
        window.location = `${window.appBaseUrl}${url}`;
    }
</script>

<style>
    * {
        margin: 0px;
        padding: 0px;
        box-sizing: border-box;
    }

    body {
        height: 100%;
    }

    .body_container {
        display: flex;
        flex-direction: column;
        background-color: #FFF8F0;
        min-height: 100vh;
    }

    main {
        flex: 1;
        padding-left: 0.5em;
        padding-right: 0.5em;
    }

    footer {
        background: #3E3C3E;
        color: #fff;
        text-align: center;
        padding: 1rem;
    }

    .contents {
        width: 100%;
        height: fit-content;
    }

</style>

