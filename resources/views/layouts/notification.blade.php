<script>
    function hideNotification() {
        document.getElementById('notifications_box').style.display = 'none';
    }

    function showNotification() {
        document.getElementById('notifications_box').style.display = 'block';
    }
</script>


<div class="notification" onclick="hideNotification()" id="notifications_box">

    {{-- do not show notifications if not provided any --}}
    @isset($notification)
        <script>
            showNotification();
        </script>
        @foreach($notification as $item)
            <p>{{$item}}</p>
        @endforeach
    @endisset

    @if(session('notification'))
        <script>
            showNotification();
        </script>
        @foreach(session('notification') as $item)
            <p>{{ $item }}</p>
        @endforeach
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
</div>

<style>
.notification {
    background-color: #9DD9D2;
    width: 100%;
    height: fit-content;

    font-size: 1.5em;

    border-radius: 20px;

    padding: 0.3em;
    margin-bottom: 0.3em;

    display: none;
}

.notification:hover {
    background-color: #9ECAC4;
}

</style>


