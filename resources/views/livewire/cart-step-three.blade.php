<div class="mb-4">
    {{-- <h1>Sipariş Onayı</h1>
    <button wire:click="giveOrder">sipariş ver</button>
    <h1 class="text-4xl">{{ session()->get('selected-address-for-cart') }}</h1>
    <button wire:click='showSelectedAddress'>show selected address</button> --}}
    <section {{-- wire:poll.10s --}} class="bg-white py-4 antialiased dark:bg-gray-900">
        <div class="mx-auto max-w-screen-xl">
            <div class="flex  gap-6">
                <div class="w-8/12 flex items-center justify-between ">
                    <h2 class="text-3xl font-thin text-gray-900">Confirm Your Order</h2>
                </div>
                <div class="w-4/12"></div>
            </div>

            <div class="mt-6 gap-6 flex items-start {{-- bg-blue-400 --}}">
                <div class="mx-auto w-8/12 lg:max-w-2xl xl:max-w-4xl {{-- bg-blue-400 --}}">
                    <div class="space-y-6 {{-- bg-green-300 --}}">

                        @forelse ($this->cartItems as $item)
                            <div wire:key='{{ $item->id }}'
                                class="rounded-lg border border-gray-200 bg-white p-2 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div
                                    class="space-y-4 md:flex md:items-center {{-- md:justify-between --}} md:gap-6 md:space-y-0">
                                    <a href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}"
                                        class="shrink-0 h-24 w-24 flex items-center {{-- bg-main-red --}}">
                                        <img class="w-full h-auto flex-1"
                                            src="{{ asset('storage/' . $item->product->image) }}" alt="imac image" />
                                    </a>
                                    <div class="w-full min-w-0 flex-1 space-y-4  md:max-w-md {{-- bg-green-300 --}}">
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-thin text-xl">
                                                {{ $item->quantity }}
                                            </h3>
                                            <svg class="h-5 w-5 mt-2" aria-hidden="true"
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
                                            {{ App\Helpers::formatPrice($item->item_total_price) }} TL</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                        {{-- <button wire:click='showSelectedAddress'>show selected address</button> --}}
                    </div>
                </div>
                <div class="mx-auto w-4/12 {{-- flex-1 --}} space-y-6 {{-- bg-green-400 --}}">
                    <div
                        class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-xl font-thin text-nowrap text-gray-900  {{-- bg-orange-300 --}}">
                            Order
                            summary</p>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base text-nowrap font-normal text-gray-500 dark:text-gray-400">
                                        Original price
                                    </dt>
                                    <dd class="text-base font-medium text-gray-900 dark:text-white">
                                        {{ App\Helpers::formatPrice($this->cart->subtotal()) }} TL</dd>
                                </dl>

                                {{-- <dl class="flex items-center justify-between gap-4">
                                  <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Savings</dt>
                                  <dd class="text-base font-medium text-green-600">-$299.00</dd>
                              </dl>

                              <dl class="flex items-center justify-between gap-4">
                                  <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Store Pickup
                                  </dt>
                                  <dd class="text-base font-medium text-gray-900 dark:text-white">$99</dd>
                              </dl>

                              <dl class="flex items-center justify-between gap-4">
                                  <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Tax</dt>
                                  <dd class="text-base font-medium text-gray-900 dark:text-white">$799</dd>
                              </dl> --}}
                            </div>

                            <dl
                                class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                                <dd class="text-base font-bold text-gray-900 dark:text-white">
                                    {{ App\Helpers::formatPrice($this->cart->subtotal()) }} TL</dd>
                            </dl>
                        </div>

                        <div class="flex items-center justify-center gap-2">
                            @if (count($this->cartItems) > 0)
                                <button type="button" wire:click='giveOrder' {{-- @click="$dispatch('set-cart-step',{step:3})" --}}
                                    class="inline-flex items-center gap-2 text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">
                                    <span>Give Order</span>
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                                    </svg>
                                </button>
                            @else
                                <a href="{{ route('home') }}"
                                    class="cursor-pointer inline-flex items-center gap-2 text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">
                                    <span>{{ __('frontend.cart.start-shopping') }}</span>
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
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
                        /* (!$this->selectedAddress ?? $this->defaultAddress->is_default == 1) ||
                         $this->defaultAddress->id == $address->id */ 'border-gray-200 text-gray-500' =>
                            $this->selectedAddress,
                    ])>
                        <div class="block  flex-1">
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
    {{-- <h1>Adres Seçimi</h1> --}}
    {{-- <button wire:click='next' --}} {{-- @click="$dispatch('set-cart-step',{step:3})" --}}{{-- >devam et</button> --}}
</div>
