<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IIS Project</title>
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
                <div class="div_center">
                    <div class="grid_horizontal">
                        <a class="text_link" href="https://www.stud.fit.vutbr.cz/~xfeket01/IIS/doc.html" >Documentation</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>  {{-- body_container --}}


{{-- if user was redirected with open_login, open login outright --}}
@isset($info['open_login'])
    <script>
        showLogin();
    </script>
@endisset

@if(session('info'))
    @isset($info['open_login'])
        <script>
            showLogin();
        </script>
    @endisset
@endif

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
        background-color: #F4EED5;
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

</body>
</html>
