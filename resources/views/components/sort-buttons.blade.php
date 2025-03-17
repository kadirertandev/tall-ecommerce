@props(['column', 'title', 'sortBy', 'sortDir'])
<button class="flex items-center w-full">
    <span>{{ $title }}</span>
    @if ($sortBy !== $column)
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
        </svg>
        {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 2048 2048">
            <path fill="black"
                d="m1069 499l-90 90l-338-337l-1 1796H512l1-1799l-340 340l-90-90L576 6zm807 960l91 90l-493 493l-494-493l91-90l338 338l-1-1797h128l1 1798z" />
        </svg> --}}
    @elseif($sortDir == 'asc')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
        </svg>
        {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 2048 2048">
            <path fill="black" d="m1453 499l-90 90l-338-337l-1 1796H896l1-1799l-340 340l-90-90L960 6z" />
        </svg> --}}
    @elseif($sortDir == 'desc')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
        {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 2048 2048">
            <path fill="black" d="m1364 1459l91 90l-493 493l-494-493l91-90l338 338L896 3h128l1 1795z" />
        </svg> --}}
    @endif
</button>
