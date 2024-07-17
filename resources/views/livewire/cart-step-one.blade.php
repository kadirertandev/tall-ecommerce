<div class="mb-4">
    <section {{-- wire:poll.10s --}} class="py-4 antialiased bg-white dark:bg-gray-900">
        <div class="max-w-screen-xl mx-auto">
            <div class="flex gap-6">
                <div class="flex items-center justify-between w-8/12 ">
                    <h2 class="text-3xl font-thin text-gray-900">{{ __('frontend.cart.shopping-cart') }}</h2>
                    @if (count($this->cartItems) > 0)
                        <p>{{ __('frontend.cart.cart-total-items-message', ['x' => count($this->cartItems)]) }}</p>
                    @endif
                </div>
                <div class="w-4/12"></div>
            </div>

            <div class="mt-6 gap-6 flex items-start {{-- bg-blue-400 --}}">
                <div class="mx-auto w-8/12 lg:max-w-2xl xl:max-w-4xl {{-- bg-red-400 --}}">
                    <div class="space-y-6">
                        @forelse ($this->cartItems as $item)
                            <div wire:key='{{ $item->id }}'
                                class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                    <a href="#" class="flex items-center w-24 h-24 shrink-0">
                                        <img class="flex-1 w-full h-auto"
                                            src="{{ asset('storage/' . $item->product->image) }}" alt="imac image" />
                                    </a>
                                    <div class="flex-1 w-full min-w-0 space-y-4 md:max-w-md">
                                        <a class="text-base font-medium text-gray-900 hover:underline dark:text-white"
                                            href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}">{{ $item->product->name }}</a>
                                        <div class="flex items-center gap-4">
                                            <livewire:add-to-favorites-button :key='$item->product->id' :product_slug="$item->product->slug"
                                                type="cart" />

                                            <button type="button"
                                                @click="$dispatch('remove-from-cart-modal', {cartItemId: {{ $item->id }}})"
                                                class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                                                <svg class="me-1.5 h-5 w-5" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18 17.94 6M18 18 6.06 6" />
                                                </svg>
                                                <span>Remove</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between md:justify-end">

                                        <p class="flex items-center gap-4 text-gray-500">
                                            {{-- <span>{{ __('frontend.cart.quantity') }}</span> --}}
                                            <button {{-- wire:key='btn-decrease-{{ $item->id }}' --}}
                                                wire:click='decreaseQuantity({{ $item->id }})' {{-- @click="$dispatch('decrease-quantity', {id: {{ $item->id }}})" --}}
                                                class="p-4 rounded-full bg-gray-50 hover:bg-gray-100 group">
                                                <svg class="w-3 h-3 text-gray-800 group-hover:text-red-500 dark:text-white"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                                                </svg>
                                            </button>

                                            <span>{{ $item->quantity }}</span>

                                            <button wire:click='increaseQuantity({{ $item->id }})'
                                                {{-- @click="$dispatch('increase-quantity',{id:{{ $item->id }}})" --}}
                                                class="p-4 rounded-full bg-gray-50 hover:bg-gray-100 group">
                                                <svg class="w-3 h-3 text-gray-800 group-hover:text-teal-500 dark:text-white"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5" />
                                                </svg>
                                            </button>
                                        </p>
                                        <div class="text-end md:order-4 md:w-32">
                                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                                {{ App\Helpers::formatPrice($item->item_total_price) }} TL</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div
                                class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-red-500"
                                        viewBox="0 0 16 16">
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
                    </div>
                </div>

                <div class="mx-auto w-4/12 {{-- flex-1 --}} space-y-6 {{-- bg-green-400 --}}">
                    <div
                        class="p-4 space-y-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-xl font-thin text-nowrap text-gray-900  {{-- bg-orange-300 --}}">
                            Order
                            summary</p>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-500 text-nowrap dark:text-gray-400">
                                        Original price
                                    </dt>
                                    <dd class="text-base font-medium text-gray-900 dark:text-white">
                                        {{ App\Helpers::formatPrice($this->cart?->subtotal()) }} TL</dd>
                                </dl>
                            </div>

                            <dl
                                class="flex items-center justify-between gap-4 pt-2 border-t border-gray-200 dark:border-gray-700">
                                <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                                <dd class="text-base font-bold text-gray-900 dark:text-white">
                                    {{ App\Helpers::formatPrice($this->cart?->subtotal()) }} TL</dd>
                            </dl>
                        </div>

                        <div class="flex items-center justify-center gap-2">
                            @if (count($this->cartItems) > 0)
                                <button type="button" @click="$dispatch('set-cart-step',{step:2})"
                                    class="inline-flex items-center gap-2 text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">
                                    <span>{{ __('frontend.cart.continue') }}</span>
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                                    </svg>
                                </button>
                            @else
                                <a href="{{ route('home') }}"
                                    class="cursor-pointer inline-flex items-center gap-2 text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">
                                    <span>{{ __('frontend.cart.start-shopping') }}</span>
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
