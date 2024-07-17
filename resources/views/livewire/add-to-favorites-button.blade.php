<div>
    @auth
        @php
            $buttonClass =
                $type === 'show'
                    ? 'group flex relative items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border-2 shadow-xl border-gray-200 hover:bg-red-500 focus:z-10 dark:hover:text-white'
                    : ($type === 'cart'
                        ? 'inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 hover:underline dark:text-gray-400 dark:hover:text-white'
                        : 'absolute max-w-md:bottom-0 max-w-md:right-1/2 max-w-md:left-1/2 md:right-0 md:top-0');

            $svgClass =
                $type === 'show'
                    ? 'w-5 h-5 -ms-2 me-2 group-hover:fill-white'
                    : ($type === 'cart'
                        ? 'me-1.5 h-5 w-5'
                        : 'w-10 h-10 -ms-2 me-2 group-hover:fill-white');

            $svgPath = !$this->user->favorites->contains($this->product)
                ? 'M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z'
                : 'M3.28 2.22a.75.75 0 1 0-1.06 1.06l1.855 1.856a5.375 5.375 0 0 0-.5 8.044l7.895 7.896a.75.75 0 0 0 1.06 0l3.744-3.742l4.445 4.447a.75.75 0 0 0 1.061-1.061zm17.152 10.959l-2.036 2.035L7.19 4.008a5.36 5.36 0 0 1 3.986 1.57l.823.824l.82-.822a5.38 5.38 0 0 1 7.613 7.599';
        @endphp

        @if (!$this->user->favorites->contains($this->product))
            <button wire:click='addToFavorites' class="{{ $buttonClass }}" role="button">
                <svg class="{{ $svgClass }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="{{ $svgPath }}" />
                </svg>
                @if ($showLabel)
                    <span>{{ __('frontend.favorites.add-to-favorites') }}</span>
                @endif
            </button>
        @else
            <button wire:click='removeFromFavorites' class="{{ $buttonClass }}" role="button">
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="{{ $svgClass }}" viewBox="0 0 24 24"
                    fill="#ff0000">
                    <path d="{{ $svgPath }}" />
                </svg>
                @if ($showLabel)
                    <span>{{ __('frontend.favorites.remove-from-favorites') }}</span>
                @endif
            </button>
        @endif
    @endauth

    @guest
        <a @click="$dispatch('add-to-favorites-guest-error')"
            class="group flex relative items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border-2 shadow-xl border-gray-200 hover:bg-red-500 focus:z-10 dark:hover:text-white "
            role="button">
            <svg class="w-5 h-5 -ms-2 me-2 group-hover:fill-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z" />
            </svg>
            <span>{{ __('frontend.favorites.add-to-favorites') }}</span>
        </a>
    @endguest
</div>
