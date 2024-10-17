<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/header_footer.css" rel="stylesheet">
    <link href="/css/registration.css" rel="stylesheet">
    <script src="/navigation.js"></script>
</head>
<body>
    <div class="body grid-container">
        <div class="page_header grid-container grid-container-fit" id="page_header">
            <button onclick="window.location.href='{{ url('home') }}'" >
                Home
            </button>

            <button onclick="window.location.href='{{ url('search') }}'">
                Search
            </button>

            @if ($user == NULL)
                <button onclick="window.location.href='{{ url('register') }}'">
                    Register
                </button>
                <button onclick="window.location.href='{{ url('login') }}'">
                    Login
                </button>
            @else
                @if ($user->role === 'admin')
                    <button onclick="window.location.href='{{ url('admin/dashboard') }}'">
                        Admin
                    </button>
                @endif
                <button onclick="window.location.href='{{ url('profile') }}'">
                    My profile
                </button>
                <button onclick="window.location.href='{{ url('logout') }}'">
                    Logout
                </button>
            @endif
        </div>

        <div class="contents" id="contents">
            @yield('content')
        </div>

        <div class="page_footer" id="page_footer">
            <p>License</p>
        </div>
    </div>
</body>
</html>
