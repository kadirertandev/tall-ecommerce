<x-header-meta :title="$title ?? ''" />
@if (request()->routeIs('auth.user.cart'))
    <x-header :nav1=false :nav2=false :nav3=false :nav2OnlyLogo=true />
    {{-- <x-header :nav1=true :nav2=true :nav3=true /> --}}
@else
    <x-header :nav1=true :nav2=true :nav3=true />
@endif

{{ $slot }}

<x-footer />
<x-footer-meta>
    <x-slot:script>
        @yield('script')
    </x-slot>
</x-footer-meta>
