<div>
    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" data-dropdown-trigger="hover"
        data-dropdown-offset-distance="24"
        class="group hover:text-main-red focus:text-main-red font-medium text-sm inline-flex items-center gap-1 rounded-md p-1"
        type="button">
        @if (!auth()->user()->profile_image)
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 group-hover:fill-main-red">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        @endif
        <h1 class="flex flex-col text-left">
            {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}
        </h1>
        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 4 4 4-4" />
        </svg>
    </button>

    <!-- Dropdown menu -->
    <div id="dropdown"
        class="z-[31] hidden bg-white divide-y divide-gray-100 {{-- ring-1 ring-main-red --}} rounded-md shadow-lg min-w-44 dark:bg-gray-700">
        <div class="grid grid-cols-2 gap-2">
            <ul class="pb-3 text-sm text-gray-700 " aria-labelledby="dropdownDefaultButton">
                <li>
                    <span class="text-md font-bold font-roboto block py-1.5 px-3">Listelerim</sp>
                </li>
                <li>
                    <a href="{{ route('login') }}" class="hover:text-main-red hover:underline block py-1.5 px-3">Liste
                        oluşturun</a>
                </li>
            </ul>
            <ul class="pb-3 text-sm text-gray-700 " aria-labelledby="dropdownDefaultButton">
                <li>
                    <span class="text-md font-bold font-roboto block py-1.5 px-3">Hesabım</sp>
                </li>
                <li>
                    <a href="{{ route('auth.user.profile') }}"
                        class="hover:text-main-red hover:underline block py-1.5 px-3">Profile</a>
                </li>
                <li>
                    <a href="{{ route('auth.user.favorites') }}"
                        class="hover:text-main-red hover:underline block py-1.5 px-3">Favorites</a>
                </li>
                <li>
                    <a href="{{ route('auth.user.orders') }}"
                        class="hover:text-main-red hover:underline block py-1.5 px-3">Orders</a>
                </li>
                <li>
                    <a href="{{ route('auth.user.reviews') }}"
                        class="hover:text-main-red hover:underline block py-1.5 px-3">Reviews</a>
                </li>
                <li>
                    <a href="{{ route('auth.user.addresses') }}"
                        class="hover:text-main-red hover:underline block py-1.5 px-3">Addresses</a>
                </li>
                @can('admin')
                    <li>
                        <a href="{{ route('roles-permissions') }}"
                            class="hover:text-main-red hover:underline block py-1.5 px-3">Roles Permissions</a>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
</div>
