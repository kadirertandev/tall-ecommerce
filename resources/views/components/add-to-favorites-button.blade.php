@props(['product', 'class', 'svg'])

@auth
    @if (auth()->user->favorites->contains($this->product))
        <x-add-to-favorites-button :product="$this->product" class="{{ $class }}" />
    @else
    @endif
@endauth
@guest
    <a href="{{ route('login') }}" id="btnAddToFavorites"
        class="group flex relative items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border-2 shadow-xl border-gray-200 hover:bg-red-500 focus:z-10 dark:hover:text-white "
        role="button">
        <svg class="w-5 h-5 -ms-2 me-2 group-hover:fill-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z" />
        </svg>
        <span>{{ __('frontend.add-to-favorites') }}</span>
    </a>
@endguest
<button wire:key='btnAddToFavorites' id="btnAddToFavorites" {{-- wire:click='addToFavorites' --}}
    @click="$dispatch('add-to-favorites', {product: {{ $product }}})" {{-- class="group flex relative items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border-2 shadow-xl border-gray-200 hover:bg-red-500 focus:z-10 dark:hover:text-white" --}}
    @class([
        'group flex relative items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border-2 shadow-xl border-gray-200 hover:bg-red-500 focus:z-10 dark:hover:text-white' => !$class,
    ]) role="button">
    @if ($svg)
        <svg class="w-5 h-5 -ms-2 me-2 group-hover:fill-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z" />
        </svg>
    @endif
    <span>{{ __('frontend.favorites.add-to-favorites') }}</span>
</button>
