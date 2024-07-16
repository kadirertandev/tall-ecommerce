<x-header-meta />
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
                        <svg class="w-4 h-4 me-2 {{ request()->routeIs('auth.user.profile') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                        </svg>
                        {{ __('frontend.auth.dropdown-on-nav.profile') }}
                    </a>
                </li>
                <li>
                    <a wire:navigate {{-- href="{{ !request()->routeIs('auth.user.favorites') ? route('auth.user.favorites') : 'javascript:void(0);' }}" --}} href="{{ route('auth.user.favorites') }}"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ request()->routeIs('auth.user.favorites') ? 'bg-blue-700 text-white' : 'bg-gray-50 hover:text-gray-900  hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 me-2 {{ request()->routeIs('auth.user.favorites') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 18">
                            <path
                                d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                        </svg>
                        {{ __('frontend.auth.dropdown-on-nav.favorites') }}
                    </a>
                </li>
                <li>
                    <a wire:navigate {{-- href="{{ !request()->routeIs('auth.user.orders') ? route('auth.user.orders') : 'javascript:void(0);' }}" --}} href="{{ route('auth.user.orders') }}"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ request()->routeIs('auth.user.orders') ? 'bg-blue-700 text-white' : 'bg-gray-50 hover:text-gray-900  hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 me-2 {{ request()->routeIs('auth.user.orders') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 18">
                            <path
                                d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                        </svg>
                        {{ __('frontend.auth.dropdown-on-nav.orders') }}
                    </a>
                </li>
                <li>
                    <a wire:navigate {{-- href="{{ !request()->routeIs('auth.user.reviews') ? route('auth.user.reviews') : 'javascript:void(0);' }}" --}} href="{{ route('auth.user.reviews') }}"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ request()->routeIs('auth.user.reviews') ? 'bg-blue-700 text-white' : 'bg-gray-50 hover:text-gray-900  hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 me-2 {{ request()->routeIs('auth.user.reviews') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 18">
                            <path
                                d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                        </svg>
                        {{ __('frontend.auth.dropdown-on-nav.reviews') }}
                    </a>
                </li>
                <li>
                    <a wire:navigate
                        href="{{ !request()->routeIs('auth.user.addresses') ? route('auth.user.addresses') : 'javascript:void(0);' }}"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ request()->routeIs('auth.user.addresses') ? 'bg-blue-700 text-white' : 'bg-gray-50 hover:text-gray-900  hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 me-2 {{ request()->routeIs('auth.user.addresses') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 18">
                            <path
                                d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                        </svg>
                        {{ __('frontend.auth.dropdown-on-nav.addresses') }}
                    </a>
                </li>
                <li>
                    <a wire:navigate
                        href="{{ !request()->routeIs('auth.user.change-password') ? route('auth.user.change-password') : 'javascript:void(0);' }}"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full text-nowrap {{ request()->routeIs('auth.user.change-password') ? 'bg-blue-700 text-white' : 'bg-gray-50 hover:text-gray-900  hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 me-2 {{ request()->routeIs('auth.user.change-password') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 18">
                            <path
                                d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
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
