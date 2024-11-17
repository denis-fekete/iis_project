<div class="page_header grid-container grid-container-fit" id="page_header">
    <button onclick="window.location.href='{{ url('home') }}'" >
        Home
    </button>

    <button onclick="window.location.href='{{ url('conferences/search/All;Name;asc') }}'">
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
