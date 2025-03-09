@props(['nav1', 'nav2', 'nav2OnlyLogo', 'nav3'])
<header>
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
