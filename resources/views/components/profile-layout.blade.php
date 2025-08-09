@props(['title' => ''])
<x-header-meta :$title />
<div class="min-h-screen">
    <x-header :nav1=true :nav2=true :nav3=true :nav2OnlyLogo=false />
    <div class="main-container">

        <livewire:auth-avatar-at-profile />

        <div class="md:flex bg-white items-start">
            <ul
                class="md:sticky md:top-0 flex-column space-y space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400 md:me-4 mb-4 md:mb-0">
                <li>
                    <a wire:navigate {{-- href="{{ !request()->routeIs('auth.user.profile') ? route('auth.user.profile') : 'javascript:void(0);' }}" --}} href="{{ route('auth.user.profile') }}"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ request()->routeIs('auth.user.profile') ? 'bg-blue-700 text-white' : 'bg-gray-50 hover:text-gray-900  hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5 me-2 {{ request()->routeIs('auth.user.profile') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        {{ __('frontend.auth.dropdown-on-nav.profile') }}
                    </a>
                </li>
                <li>
                    <a wire:navigate {{-- href="{{ !request()->routeIs('auth.user.favorites') ? route('auth.user.favorites') : 'javascript:void(0);' }}" --}} href="{{ route('auth.user.favorites') }}"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ request()->routeIs('auth.user.favorites') ? 'bg-blue-700 text-white' : 'bg-gray-50 hover:text-gray-900  hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5 me-2 {{ request()->routeIs('auth.user.favorites') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                        {{ __('frontend.auth.dropdown-on-nav.favorites') }}
                    </a>
                </li>
                <li>
                    <a wire:navigate {{-- href="{{ !request()->routeIs('auth.user.orders') ? route('auth.user.orders') : 'javascript:void(0);' }}" --}} href="{{ route('auth.user.orders') }}"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ request()->routeIs('auth.user.orders') ? 'bg-blue-700 text-white' : 'bg-gray-50 hover:text-gray-900  hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 me-2 {{ request()->routeIs('auth.user.orders') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"
                            viewBox="0 0 32 32">
                            <path fill="currentColor"
                                d="M0 6v2h19v15h-6.156c-.446-1.719-1.992-3-3.844-3c-1.852 0-3.398 1.281-3.844 3H4v-5H2v7h3.156c.446 1.719 1.992 3 3.844 3c1.852 0 3.398-1.281 3.844-3h8.312c.446 1.719 1.992 3 3.844 3c1.852 0 3.398-1.281 3.844-3H32v-8.156l-.063-.157l-2-6L29.72 10H21V6zm1 4v2h9v-2zm20 2h7.281L30 17.125V23h-1.156c-.446-1.719-1.992-3-3.844-3c-1.852 0-3.398 1.281-3.844 3H21zM2 14v2h6v-2zm7 8c1.117 0 2 .883 2 2s-.883 2-2 2s-2-.883-2-2s.883-2 2-2m16 0c1.117 0 2 .883 2 2s-.883 2-2 2s-2-.883-2-2s.883-2 2-2" />
                        </svg>
                        {{ __('frontend.auth.dropdown-on-nav.orders') }}
                    </a>
                </li>
                <li>
                    <a wire:navigate {{-- href="{{ !request()->routeIs('auth.user.reviews') ? route('auth.user.reviews') : 'javascript:void(0);' }}" --}} href="{{ route('auth.user.reviews') }}"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ request()->routeIs('auth.user.reviews') ? 'bg-blue-700 text-white' : 'bg-gray-50 hover:text-gray-900  hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 me-2 {{ request()->routeIs('auth.user.reviews') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"
                            viewBox="0 0 32 32">
                            <path fill="currentColor"
                                d="m16 8l1.912 3.703l4.088.594L19 15l1 4l-4-2.25L12 19l1-4l-3-2.703l4.2-.594z" />
                            <path fill="currentColor"
                                d="M17.736 30L16 29l4-7h6a1.997 1.997 0 0 0 2-2V8a1.997 1.997 0 0 0-2-2H6a1.997 1.997 0 0 0-2 2v12a1.997 1.997 0 0 0 2 2h9v2H6a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4h20a4 4 0 0 1 4 4v12a4 4 0 0 1-4 4h-4.835Z" />
                        </svg>
                        {{ __('frontend.auth.dropdown-on-nav.reviews') }}
                    </a>
                </li>
                <li>
                    <a wire:navigate
                        href="{{ !request()->routeIs('auth.user.addresses') ? route('auth.user.addresses') : 'javascript:void(0);' }}"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ request()->routeIs('auth.user.addresses') ? 'bg-blue-700 text-white' : 'bg-gray-50 hover:text-gray-900  hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 me-2 {{ request()->routeIs('auth.user.addresses') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M7 2a2 2 0 0 0-2 2v1a1 1 0 0 0 0 2v1a1 1 0 0 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H7Zm3 8a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm-1 7a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3 1 1 0 0 1-1 1h-6a1 1 0 0 1-1-1Z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ __('frontend.auth.dropdown-on-nav.addresses') }}
                    </a>
                </li>
                <li>
                    <a wire:navigate
                        href="{{ !request()->routeIs('auth.user.change-password') ? route('auth.user.change-password') : 'javascript:void(0);' }}"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full text-nowrap {{ request()->routeIs('auth.user.change-password') ? 'bg-blue-700 text-white' : 'bg-gray-50 hover:text-gray-900  hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5 me-2 {{ request()->routeIs('auth.user.change-password') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        {{ __('frontend.auth.change-password') }}
                    </a>
                </li>
            </ul>
            {{ $content }}
        </div>


    </div>
</div>
<x-footer />
<x-footer-meta>
    <x-slot:script>
        <script></script>
    </x-slot>
</x-footer-meta>
