<div
    class="relative ml-3"
    x-data="{ showProfileDropdown: false }"
    @toggle-profile-dropdown.window="showProfileDropdown = !showProfileDropdown"
    @click.outside="showProfileDropdown = false"

>

    <x-app.navbar.dropdown.button/>

    <x-app.navbar.dropdown.content/>

</div>
