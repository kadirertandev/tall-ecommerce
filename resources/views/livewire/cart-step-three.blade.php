<div class="mb-4">
    <section {{-- wire:poll.10s --}} class="py-4 antialiased bg-white dark:bg-gray-900">
        <div class="mx-auto max-w-(--breakpoint-xl)">
            <div class="flex gap-6">
                <div class="flex items-center justify-between w-8/12 ">
                    <h2 class="text-3xl font-thin text-gray-900">Confirm Your Order</h2>
                </div>
                <div class="w-4/12"></div>
            </div>

            <div class="flex items-start gap-6 mt-6">
                <div class="w-8/12 mx-auto lg:max-w-2xl xl:max-w-4xl">
                    <div class="space-y-6">

                        @forelse ($this->cartItems as $item)
                            <div wire:key='{{ $item->id }}'
                                class="p-2 bg-white border border-gray-200 rounded-lg shadow-xs dark:border-gray-700 dark:bg-gray-800">
                                <div class="space-y-4 md:flex md:items-center md:gap-6 md:space-y-0">
                                    <a href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}"
                                        class="flex items-center w-24 h-24 shrink-0">
                                        <img class="flex-1 w-full h-auto"
                                            src="{{ asset('storage/' . $item->product->image) }}" alt="imac image" />
                                    </a>
                                    <div class="flex-1 w-full min-w-0 space-y-4 md:max-w-md">
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-xl font-thin">
                                                {{ $item->quantity }}
                                            </h3>
                                            <svg class="w-5 h-5 mt-2" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18 17.94 6M18 18 6.06 6" />
                                            </svg>

                                            <a class="text-xl font-thin text-gray-900 hover:underline dark:text-white"
                                                href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}">
                                                {{ $item->product->name }}
                                            </a>
                                        </div>
                                        <p class="text-base font-bold text-gray-900 dark:text-white">
                                            {{ App\Helpers::formatPrice($item->totalPrice()) }} TL</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
                <div class="w-4/12 mx-auto space-y-6">
                    <div
                        class="p-4 space-y-4 bg-white border border-gray-200 rounded-lg shadow-xs dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-xl font-thin text-gray-900 text-nowrap">
                            Order
                            summary</p>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-500 text-nowrap dark:text-gray-400">
                                        Original price
                                    </dt>
                                    <dd class="text-base font-medium text-gray-900 dark:text-white">
                                        {{ App\Helpers::formatPrice($this->cart->subtotal()) }} TL</dd>
                                </dl>
                            </div>

                            <dl
                                class="flex items-center justify-between gap-4 pt-2 border-t border-gray-200 dark:border-gray-700">
                                <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                                <dd class="text-base font-bold text-gray-900 dark:text-white">
                                    {{ App\Helpers::formatPrice($this->cart->subtotal()) }} TL</dd>
                            </dl>
                        </div>

                        <div class="flex items-center justify-center gap-2">
                            @if (count($this->cartItems) > 0)
                                <button type="button" wire:click='giveOrder'
                                    class="inline-flex items-center gap-2 text-white bg-linear-to-r! from-teal-400! via-teal-500! to-teal-600! hover:bg-linear-to-br! focus:ring-2 focus:outline-hidden focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">
                                    <span>Give Order</span>
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                                    </svg>
                                </button>
                            @else
                                <a href="{{ route('home') }}"
                                    class="cursor-pointer inline-flex items-center gap-2 text-white bg-linear-to-r! from-teal-400! via-teal-500! to-teal-600! hover:bg-linear-to-br! focus:ring-2 focus:outline-hidden focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">
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
                    <div for="hosting-small-{{ $this->finalAddress->id }}" @class([
                        'inline-flex items-center justify-between w-full p-5  bg-white border  rounded-lg cursor-pointer  peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100',
                        'border-blue-600 text-blue-600' =>
                            $this->selectedAddress == $this->finalAddress->id,
                        'border-gray-200 text-gray-500' => $this->selectedAddress,
                    ])>
                        <div class="flex-1 block">
                            <h1 class="w-full text-lg font-semibold">{{ $this->finalAddress->title }}
                            </h1>
                            <h2 class="w-full">{{ $this->finalAddress->neighborhood }}</h2>
                            <p class="w-full">{{ $this->finalAddress->address_line }}</p>
                            <h2 class="w-full">{{ $this->finalAddress->district }} /
                                {{ $this->finalAddress->city }}</h2>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 " viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12 6.5A2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5A2.5 2.5 0 0 1 9.5 9A2.5 2.5 0 0 1 12 6.5M12 2a7 7 0 0 1 7 7c0 5.25-7 13-7 13S5 14.25 5 9a7 7 0 0 1 7-7m0 2a5 5 0 0 0-5 5c0 1 0 3 5 9.71C17 12 17 10 17 9a5 5 0 0 0-5-5" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
