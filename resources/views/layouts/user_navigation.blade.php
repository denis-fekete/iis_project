<div class="navigation">
    <button class="navigation_button" onclick="navigateTo('/home')" >
        Home
    </button>

    <button class="navigation_button" onclick="navigateTo('/conferences/search/All;Name;asc')">
        Search
    </button>

    @if ($user == NULL)
        <button class="navigation_button" onclick="showLogin()">Log In</button>
    @else
        @if ($user->role === 'admin')
            <button class="navigation_button" onclick="navigateTo('/admin/dashboard')">
                Admin
            </button>
        @endif
        <button class="navigation_button" onclick="navigateTo('/reservations/dashboard')">
            Reservations
        </button>
        <button class="navigation_button" onclick="navigateTo('/lectures/dashboard')">
            My Lectures
        </button>
        <button class="navigation_button" onclick="navigateTo('/conferences/dashboard')">
            My Conferences
        </button>
        <button class="navigation_button" onclick="navigateTo('/profile')">
            My profile
        </button>
        <button class="navigation_button" onclick="navigateTo('/auth/logout')">
            Logout
        </button>
    @endif
</div>

<style>
    .navigation {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        height: fit-content;
        margin-bottom: 1em;
    }

    .navigation_button {
        align-content: center;
        height: 7vh ;
        background-color: #FAAC40;

        color: #3E3C3E;
        font-size: 1.2em;
        font-weight: bold;

        border: 2px solid #3E3C3E;

        border-radius: 0px;
        box-shadow: none;
    }

    .navigation_button:hover{
        background-color: #F7BE58;
}
</style>


