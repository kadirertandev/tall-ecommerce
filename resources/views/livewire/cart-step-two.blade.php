<div class="mb-4">
    <section {{-- wire:poll.10s --}} class="bg-white py-4 antialiased dark:bg-gray-900">
        <div class="mx-auto max-w-screen-xl">
            <div class="flex  gap-6">
                <div class="w-8/12 flex items-center justify-between ">
                    <h2 class="text-3xl font-thin text-gray-900">{{ __('frontend.cart.address-selection') }}</h2>
                </div>
                <div class="w-4/12"></div>
            </div>
            @if (!$this->defaultAddress && count($this->nonDefaultAddresses) == 0)
                <button type="button" @click='alert("add new address")'
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mt-4 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Add new address
                </button>
            @endif
            <div class="mt-6 gap-6 flex items-start {{-- bg-blue-400 --}}">
                <div class="mx-auto w-8/12 lg:max-w-2xl xl:max-w-4xl {{-- bg-blue-400 --}}">
                    <div class="space-y-6 {{-- bg-green-300 --}}">
                        <ul class="grid w-full gap-6 md:grid-cols-2 {{-- bg-red-300 --}}">
                            {{-- {{ $this->defaultAddress }} --}}
                            {{-- {{ dd($this->defaultAddress) }} --}}
                            {{-- {{ dd($this->defaultAddress, $this->selectedAddress) }} --}}
                            @if ($this->defaultAddress)
                                <li wire:key='address-radio-{{ $this->defaultAddress->id }}'>
                                    <input wire:model.live='selectedAddress' type="radio"
                                        id="hosting-small-{{ $this->defaultAddress->id }}" name="hosting"
                                        value="{{ $this->defaultAddress->id }}" class="hidden peer" required />
                                    <label for="hosting-small-{{ $this->defaultAddress->id }}"
                                        @class([
                                            'inline-flex items-center justify-between w-full p-5  bg-white border  rounded-lg cursor-pointer  peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100',
                                            'border-blue-600 text-blue-600' =>
                                                $this->selectedAddress == $this->defaultAddress->id,
                                            'border-gray-200 text-gray-500' => $this->selectedAddress,
                                        ])>
                                        <div class="block  flex-1">
                                            <div class="flex items-center justify-between">
                                                <h1 class="w-full text-lg font-semibold">
                                                    {{ $this->defaultAddress->title }}
                                                </h1>
                                                <h1 class="uppercase font-semibold text-orange-400 text-nowrap">Default
                                                    Address</h1>
                                            </div>
                                            <h2 class="w-full">{{ $this->defaultAddress->neighborhood }}</h2>
                                            <p class="w-full">{{ $this->defaultAddress->address_line }}</p>
                                            <h2 class="w-full">{{ $this->defaultAddress->district }} /
                                                {{ $this->defaultAddress->city }}</h2>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 " viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M12 6.5A2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5A2.5 2.5 0 0 1 9.5 9A2.5 2.5 0 0 1 12 6.5M12 2a7 7 0 0 1 7 7c0 5.25-7 13-7 13S5 14.25 5 9a7 7 0 0 1 7-7m0 2a5 5 0 0 0-5 5c0 1 0 3 5 9.71C17 12 17 10 17 9a5 5 0 0 0-5-5" />
                                        </svg>
                                    </label>
                                </li>
                            @endif
                            @foreach ($this->nonDefaultAddresses as $address)
                                <li wire:key='address-radio-{{ $address->id }}'>
                                    <input wire:model.live='selectedAddress' type="radio"
                                        id="hosting-small-{{ $address->id }}" name="hosting"
                                        value="{{ $address->id }}" class="hidden peer" required />
                                    <label for="hosting-small-{{ $address->id }}" @class([
                                        'inline-flex items-center justify-between w-full p-5  bg-white border  rounded-lg cursor-pointer  peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100',
                                        'border-blue-600 text-blue-600' => $this->selectedAddress == $address->id,
                                        'border-gray-200 text-gray-500' => $this->selectedAddress != $address->id,
                                    ])>
                                        <div class="block">
                                            <h1 class="w-full text-lg font-semibold">{{ $address->title }}</h1>
                                            <h2 class="w-full">{{ $address->neighborhood }}</h2>
                                            <p class="w-full">{{ $address->address_line }}</p>
                                            <h2 class="w-full">{{ $address->district }} / {{ $address->city }}</h2>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M12 6.5A2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5A2.5 2.5 0 0 1 9.5 9A2.5 2.5 0 0 1 12 6.5M12 2a7 7 0 0 1 7 7c0 5.25-7 13-7 13S5 14.25 5 9a7 7 0 0 1 7-7m0 2a5 5 0 0 0-5 5c0 1 0 3 5 9.71C17 12 17 10 17 9a5 5 0 0 0-5-5" />
                                        </svg>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        <button wire:click='showSelectedAddress'>show selected address</button>
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
                            {{-- <button
                                class="bg-teal-500 text-white inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline hover:no-underline dark:text-primary-500">
                                Continue
                                <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                                </svg>
                            </button> --}}
                            @if (count($this->cartItems) > 0)
                                <button type="button" wire:click='next' {{-- @click="$dispatch('set-cart-step',{step:3})" --}}
                                    class="inline-flex items-center gap-2 text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">
                                    <span>{{ __('frontend.cart.continue') }}</span>
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
                        @error('address-required')
                            <div class="w-full bg-red-500 text-white text-center px-2 py-4 rounded-md font-thin">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <h1>Adres Seçimi</h1> --}}
    {{-- <button wire:click='next' --}} {{-- @click="$dispatch('set-cart-step',{step:3})" --}}{{-- >devam et</button> --}}
</div>
