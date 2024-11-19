<div class="page_header grid-container grid-container-fit" id="page_header">
    <button onclick="navigateTo('/home')" >
        Home
    </button>

    <button onclick="navigateTo('/conferences/search/All;Name;asc')">
        Search
    </button>

    @if ($user == NULL)
        <button type="button" onclick="showLogin()">Log In</button>
    @else
        @if ($user->role === 'admin')
            <button onclick="navigateTo('/admin/dashboard')">
                Admin
            </button>
        @endif
        <button onclick="navigateTo('/reservations/dashboard')">
            Reservations
        </button>
        <button onclick="navigateTo('/lectures/dashboard')">
            My Lectures
        </button>
        <button onclick="navigateTo('/conferences/dashboard')">
            My Conferences
        </button>
        <button onclick="navigateTo('/profile')">
            My profile
        </button>
        <button onclick="navigateTo('/auth/logout')">
            Logout
        </button>
    @endif
</div>


