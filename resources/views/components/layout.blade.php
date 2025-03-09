<x-header-meta :title="$title ?? ''" />

@php
    $isCartPage = request()->routeIs('auth.user.cart');
@endphp

<x-header :nav1="!$isCartPage" :nav2="!$isCartPage" :nav3="!$isCartPage" :nav2OnlyLogo="$isCartPage" />

{{ $slot }}

<x-footer />
<x-footer-meta>
    @yield('script')
</x-footer-meta>
