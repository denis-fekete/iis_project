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
            <button onclick="gotoHome()"> Home </button>
            <button onclick="gotoSearch()"> Search </button>
            <button onclick="gotoProfile()"> My profile </button>
            <button onclick="gotoAdmin()"> Admin </button>
            <button onclick="gotoRegister()"> Register </button>
            <button onclick="gotoLogin()"> Login </button>
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
