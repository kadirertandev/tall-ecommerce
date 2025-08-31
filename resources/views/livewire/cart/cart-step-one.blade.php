<x-cart-content-layout :title="trans('frontend.cart.shopping-cart')" :step="1">
    <x-slot:content>
        @forelse ($this->cartItems as $item)
            <div wire:key='{{ $item->id }}'
                class="p-4 bg-white border border-gray-200 rounded-lg shadow-xs dark:border-gray-700 dark:bg-gray-800 md:p-6">
                <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                    <a href="#" class="flex items-center w-24 h-24 shrink-0">
                        <img class="flex-1 w-full h-auto" src="{{ asset('storage/' . $item->product->image) }}"
                            alt="imac image" />
                    </a>
                    <div class="flex-1 w-full min-w-0 space-y-4 md:max-w-md">
                        <a class="text-base font-medium text-gray-900 hover:underline dark:text-white"
                            href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}">{{ $item->product->name }}</a>
                        <div class="flex items-center gap-4">
                            <livewire:user-product-favorite-button :key='$item->product->id' :productId="$item->product->id" type="cart" />

                            <livewire:cart.remove-from-cart-button :key="'remove-' . $item->id" :cartItemId="$item->id"
                                type="cart_step_one" />
                        </div>
                    </div>
                    <div class="flex items-center justify-between md:justify-end">

                        <p class="flex items-center gap-4 text-gray-500">
                            <livewire:cart.decrease-quantity-button :key="'decrease-' . $item->id" :cartItemId="$item->id" />

                            <span>{{ $item->quantity }}</span>

                            <livewire:cart.increase-quantity-button :key="'increase-' . $item->id" :cartItemId="$item->id" />
                        </p>
                        <div class="text-end md:order-4 md:w-32">
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ App\Helpers::formatPrice($item->totalPrice()) }} TL</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="p-4 bg-white border border-gray-200 rounded-lg shadow-xs dark:border-gray-700 dark:bg-gray-800 md:p-6">
                <div class="flex flex-col items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-red-500" viewBox="0 0 16 16">
                        <g fill="currentColor">
                            <path
                                d="M7.354 5.646a.5.5 0 1 0-.708.708L7.793 7.5L6.646 8.646a.5.5 0 1 0 .708.708L8.5 8.207l1.146 1.147a.5.5 0 0 0 .708-.708L9.207 7.5l1.147-1.146a.5.5 0 0 0-.708-.708L8.5 6.793z" />
                            <path
                                d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607l1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4a2 2 0 0 0 0-4h7a2 2 0 1 0 0 4a2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0a1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0a1 1 0 0 1 2 0" />
                        </g>
                    </svg>
                    <h1 class="text-3xl font-thin">{{ __('frontend.cart.cart-empty') }}</h1>
                </div>
            </div>
        @endforelse
    </x-slot:content>

    <x-slot:rightSideContent>
        <div class="flex items-center justify-center gap-2">
            @if ($this->cartItemsCount > 0)
                <button type="button" wire:click="$parent.setStep(2)"
                    class="inline-flex items-center gap-2 text-white bg-linear-to-r! from-teal-400! via-teal-500! to-teal-600! hover:bg-linear-to-br! focus:ring-2 focus:outline-hidden focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">
                    <span>{{ __('frontend.cart.continue') }}</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 12H5m14 0-4 4m4-4-4-4" />
                    </svg>
                </button>
            @else
                <a href="{{ route('home') }}"
                    class="cursor-pointer inline-flex items-center gap-2 text-white bg-linear-to-r! from-teal-500! via-teal-600! to-teal-700! hover:bg-linear-to-br! focus:ring-2 focus:outline-hidden focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">
                    <span>{{ __('frontend.cart.start-shopping') }}</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 12H5m14 0-4 4m4-4-4-4" />
                    </svg>
                </a>
            @endif
        </div>
    </x-slot:rightSideContent>
</x-cart-content-layout>
