<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="body grid-container">
    {{-- include navigation bar --}}
    @include('layouts.user_navigation')

    {{-- do not show notifications if not provided any --}}
    @if (session('notification') != null)
        <div class="notification">
            <h3>{{session('notification')}}</h3>
        </div>
    @endif

    {{-- if $info not provided set it as a empty array --}}
    @if (session('info') == null)
        @php
            $info = array();
        @endphp
    @endif

    {{-- do not show errors if not provided any --}}
    @if ($errors->any())
        <div style="background-color: red">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- include login 'popup' window --}}
    @include('layouts.auth_popup')

    {{-- add specific contents of the page --}}
    <div class="contents" id="contents">
        @yield('content')
    </div>

    <div class="page_footer" id="page_footer">
        <p>License</p>
    </div>
</div> {{-- /body --}}

{{-- if user was redirected with open_login, open login outright --}}
@isset($info['open_login'])
    <script>
        showLogin();
    </script>
@endisset

</body>
</html>

<script>
    function navigateTo(url) {
        window.location = url;
    }
</script>

<style>
.page_header {
    background-color: lightgray;
    width: 100%;
    /* height: fit-content; */
    height: 50px;
}

.body {
    margin: 0px;
    grid-template-rows: repeat(auto-fit, minmax(50px, 1fr));
}

.grid-container {
    display: grid;
}

.grid-container-fit {
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
}

.contents {
    background-color: aqua;
    width: 100%;
    /* height: 500px; */
    height: fit-content;
}

.page_footer {
    background-color: gray;
    width: 100%;
    height: 50px;
}
</style>
