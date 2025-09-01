<x-cart-content-layout :title="trans('frontend.cart.address-selection')" :step="2">
    <x-slot:content>
        <div class="space-y-6">
            <ul class="grid w-full gap-6 md:grid-cols-2">
                @foreach ($this->addresses as $address)
                    <li wire:key='address-radio-{{ $address->id }}' @class([
                        'order-first' => $address->is_default,
                    ])>
                        <input wire:model.live='selectedAddressId' type="radio" id="hosting-small-{{ $address->id }}"
                            name="hosting" value="{{ $address->id }}" class="hidden peer" required />
                        <label for="hosting-small-{{ $address->id }}" @class([
                            'inline-flex items-center justify-between w-full p-5  bg-white border  rounded-lg cursor-pointer  peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100',
                        ])>
                            <div class="flex-1 block">
                                <div class="flex items-center justify-between">
                                    <h1 class="w-full text-lg font-semibold">
                                        {{ $address->title }}
                                    </h1>
                                    @if ($address->is_default)
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
    </x-slot:content>

    <x-slot:rightSideContent>
        <div class="flex items-center justify-center gap-2">
            @if ($this->cartItemsCount > 0)
                <button type="button" wire:click='next'
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
                    class="cursor-pointer inline-flex items-center gap-2 text-white bg-linear-to-r! from-teal-400! via-teal-500! to-teal-600! hover:bg-linear-to-br! focus:ring-2 focus:outline-hidden focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">
                    <span>{{ __('frontend.cart.start-shopping') }}</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 12H5m14 0-4 4m4-4-4-4" />
                    </svg>
                </a>
            @endif
        </div>
        @error('address-required')
            <div class="w-full px-2 py-4 font-thin text-center text-white bg-red-500 rounded-md">
                {{ $message }}
            </div>
        @enderror
    </x-slot:rightSideContent>

</x-cart-content-layout>
