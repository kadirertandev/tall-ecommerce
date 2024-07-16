@props(['nav1', 'nav2', 'nav2OnlyLogo', 'nav3'])
<header>
    {{-- @if (session()->has('register-success'))
        <nav class="main-container text-center text-3xl my-2">
            {{ session('register-success') }}
        </nav>
    @endif
    @if (session()->has('login-success'))
        <nav class="absolute top-0 right-0 main-container text-center text-3xl my-2">
            {{ session('login-success') }}
        </nav>
    @endif
    @if (session()->has('logout-success'))
        <nav class="absolute top-0 right-0 main-container text-center text-3xl my-2">
            {{ session('logout-success') }}
        </nav>
    @endif --}}
    @if ($nav1 ?? false)
        @include('partials._nav1')
    @endif
    @if ($nav2 ?? false)
        @include('partials._nav2')
    @endif
    @if ($nav3 ?? false)
        @include('partials._nav3')
    @endif
    @if ($nav2OnlyLogo ?? false)
        @include('partials._nav2OnlyLogo')
    @endif
</header>
