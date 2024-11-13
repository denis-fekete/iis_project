<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('css/header_footer.css')}}" rel="stylesheet">
    <link href="{{asset('css/registration.css')}}" rel="stylesheet">
    <script src="/navigation.js"></script>
</head>
<body>
    <div class="body grid-container">
        <div class="page_header grid-container grid-container-fit" id="page_header">
            <button onclick="window.location.href='{{ url('home') }}'" >
                Home
            </button>

            <button onclick="window.location.href='{{ url('conferences/search') }}'">
                Search
            </button>

            @if ($user == NULL)
                <button type="button" onclick="showLogin()">Log In</button>
            @else
                @if ($user->role === 'admin')
                    <button onclick="window.location.href='{{ url('admin/dashboard') }}'">
                        Admin
                    </button>
                @endif
                <button onclick="window.location.href='{{ url('reservations/dashboard') }}'">
                    Reservations
                </button>
                <button onclick="window.location.href='{{ url('lectures/dashboard') }}'">
                    My Lectures
                </button>
                <button onclick="window.location.href='{{ url('conferences/dashboard') }}'">
                    My Conferences
                </button>
                <button onclick="window.location.href='{{ url('profile') }}'">
                    My profile
                </button>
                <button onclick="window.location.href='{{ url('auth/logout') }}'">
                    Logout
                </button>
            @endif
        </div>


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

        @include('layouts.auth_popup')

        <div class="contents" id="contents">
            @yield('content')
        </div>

        <div class="page_footer" id="page_footer">
            <p>License</p>
        </div>
    </div>


    @isset($info['open_login'])
        <script>
            showLogin();
        </script>
    @endisset
</body>
</html>
