<div class="mb-4">
    <section class="py-4 antialiased bg-white dark:bg-gray-900">
        <div class="max-w-screen-xl mx-auto">
            <div class="flex gap-6">
                <div class="flex items-center justify-between w-8/12 ">
                    <h2 class="text-3xl font-thin text-gray-900">{{ __('frontend.cart.address-selection') }}</h2>
                </div>
                <div class="w-4/12"></div>
            </div>

            @if (count($this->addresses) == 0)
                <button type="button" @click="$dispatch('open-address-modal', {name: 'new-address'})"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mt-4 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Add new address
                </button>

                <x-address-modal name="new-address" type="add" />
            @endif

            <div class="flex items-start gap-6 mt-6">
                <div class="w-8/12 mx-auto lg:max-w-2xl xl:max-w-4xl">
                    <div class="space-y-6">
                        <ul class="grid w-full gap-6 md:grid-cols-2" wire:poll>
                            @foreach ($this->addresses as $address)
                                <li wire:key='address-radio-{{ $address->id }}' @class([
                                    'order-first' => $this->defaultAddress?->id == $address->id,
                                ])>
                                    <input wire:model.live='selectedAddress' type="radio"
                                        id="hosting-small-{{ $address->id }}" name="hosting"
                                        value="{{ $address->id }}" class="hidden peer" required />
                                    <label for="hosting-small-{{ $address->id }}" @class([
                                        'inline-flex items-center justify-between w-full p-5  bg-white border  rounded-lg cursor-pointer  peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100',
                                        'border-blue-600 text-blue-600' => $this->selectedAddress == $address->id,
                                        'border-gray-200 text-gray-500' => $this->selectedAddress,
                                    ])>
                                        <div class="flex-1 block">
                                            <div class="flex items-center justify-between">
                                                <h1 class="w-full text-lg font-semibold">
                                                    {{ $address->title }}
                                                </h1>
                                                @if ($this->defaultAddress?->id == $address->id)
                                                    <h1 class="font-semibold text-orange-400 uppercase text-nowrap">
                                                        Default
                                                        Address</h1>
                                                @endif
                                            </div>
                                            <h2 class="w-full">{{ $address->neighborhood }}</h2>
                                            <p class="w-full">{{ $address->address_line }}</p>
                                            <h2 class="w-full">{{ $address->district }} /
                                                {{ $address->city }}</h2>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 " viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M12 6.5A2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5A2.5 2.5 0 0 1 9.5 9A2.5 2.5 0 0 1 12 6.5M12 2a7 7 0 0 1 7 7c0 5.25-7 13-7 13S5 14.25 5 9a7 7 0 0 1 7-7m0 2a5 5 0 0 0-5 5c0 1 0 3 5 9.71C17 12 17 10 17 9a5 5 0 0 0-5-5" />
                                        </svg>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="w-4/12 mx-auto space-y-6">
                    <div
                        class="p-4 space-y-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
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
                                <button type="button" wire:click='next'
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
                        @error('address-required')
                            <div class="w-full px-2 py-4 font-thin text-center text-white bg-red-500 rounded-md">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
