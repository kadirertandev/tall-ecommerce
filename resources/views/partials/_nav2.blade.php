<nav class="flex items-center justify-between my-2 main-container md:gap-8">
    <a href="{{ route('home') }}" class="text-4xl font-roboto main-red">
        <span class="font-extralight">eco</span><span class="font-extrabold">mmerce</span>
    </a>


    <div class="flex md:hidden">
        <div class="block md:hidden">
            <button data-collapse-toggle="navbar-search" type="button"
                class="inline-flex items-center justify-center w-10 h-10 p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-search" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>

                </svg>
            </button>
            <div class="absolute hidden w-full -left-0" id="navbar-search">
                <form class="mt-4">
                    <label for="default-search"
                        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 flex items-center pointer-events-none start-0 ps-3">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="default-search"
                            class="block w-full p-4 -mt-2 text-sm text-gray-900 border border-gray-300 ps-10 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search for products, categories or brands" required />
                        <button type="submit"
                            class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="block md:hidden">
            <button data-collapse-toggle="navbar-hamburger" type="button"
                class="inline-flex items-center justify-center w-10 h-10 p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-hamburger" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="hidden absolute z-[500] -left-0 w-full -mt-2" id="navbar-hamburger">
                <ul
                    class="flex flex-col mt-4 font-medium border-red-500 border-y-2 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                    <li>
                        <a href="#"
                            class="block px-3 py-2 text-gray-900 rounded hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-main-red">Login</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-3 py-2 text-gray-900 rounded hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-main-red">Register</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-3 py-2 text-gray-900 rounded hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-main-red">Favorites</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- search bar --}}
    <livewire:search />
    {{-- search bar --}}

    <div class="items-center hidden gap-4 md:flex">
        @guest
            <div>
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" data-dropdown-trigger="hover"
                    class="inline-flex items-center gap-1 p-1 text-sm font-medium rounded-md group hover:text-main-red focus:text-main-red"
                    type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 group-hover:fill-main-red">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <h1 class="flex flex-col text-left">
                        <span class="font-bold">{{ __('frontend.auth.login') }}</span>
                        <span class="font-thin">{{ __('frontend.auth.or-register') }}</span>
                    </h1>
                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <div id="dropdown"
                    class="z-[31] hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                        <li>
                            <a href="{{ route('login') }}"
                                class="block px-4 py-2 hover:text-main-red hover:underline">{{ __('frontend.auth.login') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}"
                                class="block px-4 py-2 hover:text-main-red hover:underline">{{ __('frontend.auth.register') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        @endguest
        @auth
            {{-- <livewire:auth-dropdown-on-nav /> --}}
        @endauth

        {{-- favorites --}}
        <div>
            <a href="{{ route('auth.user.favorites') }}"
                class="inline-flex items-center gap-1 p-1 text-sm font-medium rounded-md group hover:text-main-red"
                type="button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 group-hover:fill-main-red">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
                <h1>
                    {{ __('frontend.favorites.favorites') }}
                </h1>
            </a>
        </div>
        {{-- favorites --}}

        <livewire:cart-on-nav />

        @auth
            <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-2 focus:ring-red-300 "
                id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                data-dropdown-placement="bottom">
                <span class="sr-only">Open user menu</span>
                @if (auth()->user()->profile_image)
                    @if (Storage::disk('public')->exists(auth()->user()->profile_image))
                        <img class="w-8 h-8 rounded-full" src="{{ asset('/storage/' . auth()->user()->profile_image) }}"
                            alt="">
                    @endif
                @else
                    <div
                        class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                        <span
                            class="font-medium text-gray-600 dark:text-gray-300">{{ auth()->user()->placeholder_initials() }}</span>
                    </div>
                @endif
            </button>
            <!-- Dropdown menu -->
            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg dark:bg-gray-700 dark:divide-gray-600"
                id="user-dropdown">
                {{-- <div class="px-4 py-3">
                    <span class="block text-sm text-gray-900 dark:text-white">Bonnie Green</span>
                    <span class="block text-sm text-gray-500 truncate dark:text-gray-400">name@flowbite.com</span>
                </div>
                <ul class="py-2" aria-labelledby="user-menu-button">
                    <li>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Dashboard</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Settings</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Earnings</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign
                            out</a>
                    </li>
                </ul> --}}
                <div class="grid grid-cols-2 gap-2">
                    <ul class="pb-3 text-sm text-gray-700 " aria-labelledby="dropdownDefaultButton">
                        <li>
                            <span
                                class="text-md font-bold font-roboto block py-1.5 px-3">{{ __('frontend.auth.dropdown-on-nav.my-shopping-lists') }}
                                </sp>
                        </li>
                        <li>
                            <a href="{{ route('login') }}"
                                class="hover:text-main-red hover:underline block py-1.5 px-3">{{ __('frontend.auth.dropdown-on-nav.create-shopping-list') }}</a>
                        </li>
                    </ul>
                    <ul class="pb-3 text-sm text-gray-700 " aria-labelledby="dropdownDefaultButton">
                        <li>
                            <span
                                class="text-md font-bold font-roboto block py-1.5 px-3">{{ __('frontend.auth.dropdown-on-nav.my-account') }}
                            </span>
                        </li>
                        <li>
                            <a href="{{ route('auth.user.profile') }}"
                                class="hover:text-main-red hover:underline block py-1.5 px-3">{{ __('frontend.auth.dropdown-on-nav.profile') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('auth.user.favorites') }}"
                                class="hover:text-main-red hover:underline block py-1.5 px-3">{{ __('frontend.auth.dropdown-on-nav.favorites') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('auth.user.orders') }}"
                                class="hover:text-main-red hover:underline block py-1.5 px-3">{{ __('frontend.auth.dropdown-on-nav.orders') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('auth.user.reviews') }}"
                                class="hover:text-main-red hover:underline block py-1.5 px-3">{{ __('frontend.auth.dropdown-on-nav.reviews') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('auth.user.addresses') }}"
                                class="hover:text-main-red hover:underline block py-1.5 px-3">{{ __('frontend.auth.dropdown-on-nav.addresses') }}</a>
                        </li>
                        @can('admin')
                            <li>
                                <a href="{{ route('roles-permissions') }}"
                                    class="hover:text-main-red hover:underline block py-1.5 px-3">{{ __('frontend.auth.dropdown-on-nav.roles-permissions') }}</a>
                            </li>
                        @endcan
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="hover:text-main-red hover:underline block py-1.5 px-3" type="submit">
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg> --}}

                                    <h1>
                                        <span>{{ __('frontend.auth.logout') }}</span>
                                    </h1>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @endauth
    </div>
</nav>
