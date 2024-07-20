<x-header-meta :title="$title ?? ''" />
<div class="flex flex-col min-h-screen items-between">
    {{-- navbar --}}
    <nav class="bg-white border-2 border-b border-gray-200 dark:bg-gray-900">
        <div class="flex flex-wrap items-center justify-between w-full px-4 py-1">
            <div class="flex flex-wrap items-center justify-between w-2/12">
                <a href="{{ route('home') }}" class="text-4xl font-roboto main-red">
                    <span class="font-extralight">eco</span><span class="font-extrabold">mmerce</span>
                </a>
            </div>
            <div class="flex items-center justify-end w-10/12">
                <div class="flex items-center space-x-3 md:space-x-0 rtl:space-x-reverse">
                    <button type="button"
                        class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                        id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                        data-dropdown-placement="left">
                        <span class="sr-only">Open user menu</span>
                        @if (auth()->user()->profile_image ?? false)
                            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}"
                                class="w-8 h-8 rounded-full">
                        @else
                            <img src="{{ asset('storage/profile-admin.png') }}" class="w-8 h-8 rounded-full">
                        @endif
                    </button>
                    <!-- Dropdown menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                        id="user-dropdown">
                        <div class="px-4 py-3">
                            <span
                                class="block text-sm text-gray-900 dark:text-white">{{ auth()->user()->full_name() }}</span>
                            <span
                                class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ auth()->user()->email }}</span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-400 hover:text-gray-100">Profile</a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST"
                                    class="block text-sm text-gray-700 cursor-pointer hover:bg-gray-400 hover:text-gray-100">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full gap-1 px-4 py-2 text-left">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                        </svg>
                                        <span>Sign out</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <button data-collapse-toggle="navbar-user" type="button"
                        class="inline-flex items-center justify-center w-10 h-10 p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:focus:ring-gray-600"
                        aria-controls="navbar-user" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    {{-- end navbar --}}

    <section class="flex-1 bg-white flex items-stretch justify-between {{-- p-4 --}} gap-4">
        <aside id="default-sidebar" class="w-2/12 bg-teal-500" aria-label="Sidenav">
            <div
                class="h-full p-4 overflow-y-auto bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <ul class="space-y-2">
                    <li>
                        <a wire:navigate href="{{ route('admin.dashboard') }}" @class([
                            'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group',
                            'bg-gray-200 [&>svg]:text-gray-900 hover:bg-gray-200' => request()->routeIs(
                                'admin.dashboard'),
                        ])>
                            <svg aria-hidden="true"
                                class="w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    @can('view admins')
                        <li>
                            <a wire:navigate href="{{ route('admin.admins.index') }}" @class([
                                'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group',
                                'bg-gray-200 [&>svg]:text-gray-900 hover:bg-gray-200' => request()->routeIs(
                                    'admin.admins.index'),
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    viewBox="0 0 26 26">
                                    <path fill="currentColor"
                                        d="M16.563 15.9c-.159-.052-1.164-.505-.536-2.414h-.009c1.637-1.686 2.888-4.399 2.888-7.07c0-4.107-2.731-6.26-5.905-6.26c-3.176 0-5.892 2.152-5.892 6.26c0 2.682 1.244 5.406 2.891 7.088c.642 1.684-.506 2.309-.746 2.396c-3.324 1.203-7.224 3.394-7.224 5.557v.811c0 2.947 5.714 3.617 11.002 3.617c5.296 0 10.938-.67 10.938-3.617v-.811c0-2.228-3.919-4.402-7.407-5.557m-5.516 8.709c0-2.549 1.623-5.99 1.623-5.99l-1.123-.881c0-.842 1.453-1.723 1.453-1.723s1.449.895 1.449 1.723l-1.119.881s1.623 3.428 1.623 6.018c0 .406-3.906.312-3.906-.028" />
                                </svg>
                                <span class="ml-3">Admins</span>
                            </a>
                        </li>
                    @endcan
                    @can('view products')
                        <li>
                            <a wire:navigate href="{{ route('admin.products.index') }}" @class([
                                'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group',
                                'bg-gray-200 [&>svg]:text-gray-900 hover:bg-gray-200' => request()->routeIs(
                                    'admin.products.index'),
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    viewBox="0 0 32 32">
                                    <path fill="currentColor" d="M8 18h6v2H8zm0 4h10v2H8z" />
                                    <path fill="currentColor"
                                        d="M26 4H6a2.003 2.003 0 0 0-2 2v20a2.003 2.003 0 0 0 2 2h20a2.003 2.003 0 0 0 2-2V6a2.003 2.003 0 0 0-2-2m-8 2v4h-4V6ZM6 26V6h6v6h8V6h6l.001 20Z" />
                                </svg>
                                <span class="ml-3">Products</span>
                            </a>
                        </li>
                    @endcan
                    @can('view categories')
                        <li>
                            <a wire:navigate href="{{ route('admin.categories.index') }}" @class([
                                'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group',
                                'bg-gray-200 [&>svg]:text-gray-900 hover:bg-gray-200' => request()->routeIs(
                                    'admin.categories.index'),
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    viewBox="0 0 24 24">
                                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2.5">
                                        <circle cx="17" cy="7" r="3" />
                                        <circle cx="7" cy="17" r="3" />
                                        <path
                                            d="M14 14h6v5a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM4 4h6v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1z" />
                                    </g>
                                </svg>
                                <span class="ml-3">Categories</span>
                            </a>
                        </li>
                    @endcan
                    @can('view brands')
                        <li>
                            <a wire:navigate href="{{ route('admin.brands.index') }}" @class([
                                'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group',
                                'bg-gray-200 [&>svg]:text-gray-900 hover:bg-gray-200' => request()->routeIs(
                                    'admin.brands.index'),
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    viewBox="0 0 2048 2048">
                                    <path fill="currentColor"
                                        d="M1600 832q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10m192-576h256v859l-896 896l-556-558Q318 1174 37 896L933 0h859zM987 128L219 896l165 165l805-805h475V128zm933 933V384h-677l-768 768l677 677z" />
                                </svg>
                                <span class="ml-3">Brands</span>
                            </a>
                        </li>
                    @endcan
                    @can('view customers')
                        <li>
                            <a wire:navigate href="{{ route('admin.customers.index') }}" @class([
                                'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group',
                                'bg-gray-200 [&>svg]:text-gray-900 hover:bg-gray-200' => request()->routeIs(
                                    'admin.customers.index'),
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="6" r="4" fill="currentColor" />
                                    <path fill="currentColor"
                                        d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5" />
                                </svg>
                                <span class="ml-3">Customers</span>
                            </a>
                        </li>
                    @endcan
                    @can('view orders')
                        <li>
                            <a wire:navigate href="{{ route('admin.orders.index') }}" @class([
                                'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group',
                                'bg-gray-200 [&>svg]:text-gray-900 hover:bg-gray-200' => request()->routeIs(
                                    'admin.orders.index'),
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    viewBox="0 0 32 32">
                                    <path fill="currentColor"
                                        d="M0 6v2h19v15h-6.156c-.446-1.719-1.992-3-3.844-3c-1.852 0-3.398 1.281-3.844 3H4v-5H2v7h3.156c.446 1.719 1.992 3 3.844 3c1.852 0 3.398-1.281 3.844-3h8.312c.446 1.719 1.992 3 3.844 3c1.852 0 3.398-1.281 3.844-3H32v-8.156l-.063-.157l-2-6L29.72 10H21V6zm1 4v2h9v-2zm20 2h7.281L30 17.125V23h-1.156c-.446-1.719-1.992-3-3.844-3c-1.852 0-3.398 1.281-3.844 3H21zM2 14v2h6v-2zm7 8c1.117 0 2 .883 2 2s-.883 2-2 2s-2-.883-2-2s.883-2 2-2m16 0c1.117 0 2 .883 2 2s-.883 2-2 2s-2-.883-2-2s.883-2 2-2" />
                                </svg>
                                <span class="ml-3">Orders</span>
                            </a>
                        </li>
                    @endcan
                    @can('view reviews')
                        <li>
                            <a wire:navigate href="{{ route('admin.reviews.index') }}" @class([
                                'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group',
                                'bg-gray-200 [&>svg]:text-gray-900 hover:bg-gray-200' => request()->routeIs(
                                    'admin.reviews.index'),
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    viewBox="0 0 32 32">
                                    <path fill="currentColor"
                                        d="m16 8l1.912 3.703l4.088.594L19 15l1 4l-4-2.25L12 19l1-4l-3-2.703l4.2-.594z" />
                                    <path fill="currentColor"
                                        d="M17.736 30L16 29l4-7h6a1.997 1.997 0 0 0 2-2V8a1.997 1.997 0 0 0-2-2H6a1.997 1.997 0 0 0-2 2v12a1.997 1.997 0 0 0 2 2h9v2H6a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4h20a4 4 0 0 1 4 4v12a4 4 0 0 1-4 4h-4.835Z" />
                                </svg>
                                <span class="ml-3">Reviews</span>
                            </a>
                        </li>
                    @endcan
                </ul>
                <ul class="hidden pt-5 mt-5 space-y-2 border-t border-gray-200 dark:border-gray-700">
                    <li>
                        <a href="#" @class([
                            'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group',
                            'bg-gray-200 [&>svg]:text-gray-900 hover:bg-gray-200' => request()->routeIs(
                                'admin.dashboard'),
                        ])>
                            <svg aria-hidden="true"
                                class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd"
                                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-3">Docs</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" @class([
                            'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group',
                            'bg-gray-200 [&>svg]:text-gray-900 hover:bg-gray-200' => request()->routeIs(
                                'admin.dashboard'),
                        ])>
                            <svg aria-hidden="true"
                                class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z">
                                </path>
                            </svg>
                            <span class="ml-3">Components</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" @class([
                            'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group',
                            'bg-gray-200 [&>svg]:text-gray-900 hover:bg-gray-200' => request()->routeIs(
                                'admin.dashboard'),
                        ])>
                            <svg aria-hidden="true"
                                class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-3">Help</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="w-10/12 my-4">
            {{ $content }}
        </div>
    </section>
</div>

<x-footer-meta>
    <x-slot:script>
        <script></script>
    </x-slot>
</x-footer-meta>
