@props(['order'])
<div x-data="{ show: false }" x-show="show" x-on:open-order-view-modal.window="show = true"
    x-on:close-order-view-modal.window="show = false,$dispatch('order-view-modal-closed')" class="relative z-10"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" wire:ignore.self style="display: none;">
    <div class="fixed inset-0 bg-gray-500 opacity-40 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                {{-- modal content --}}
                <div class="bg-white" @click.outside="$dispatch('close-order-view-modal')">
                    {{-- header --}}
                    <div class="flex items-center justify-between p-4 border-b  dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $order?->user->full_name() }}'s Order
                        </h3>

                        <button type="button" @click="$dispatch('close-order-view-modal')"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    {{-- body --}}
                    <div class="p-4 space-y-2">

                        <div class="hidden mb-5 flex gap-2 items-start">
                            @if ($order->profile_image ?? false)
                                <img src="{{ asset('storage/' . $order->profile_image) }}"
                                    class="w-24 h-auto rounded-md">
                            @else
                                <img src="{{ asset('storage/profile-order.png') }}" class="w-24 h-auto rounded-md">
                            @endif

                            <div>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <div>
                                        <h2 class="text-md text-gray-400">First Name</h2>
                                        <h2 class="text-md">{{ $order?->first_name }}</h2>
                                    </div>
                                    <div>
                                        <h2 class="text-md text-gray-400">Last Name</h2>
                                        <h2 class="text-md">{{ $order?->last_name }}</h2>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <div>
                                        <h2 class="text-md text-gray-400">Email</h2>
                                        <h2 class="text-md">{{ $order?->email }}</h2>
                                    </div>
                                    <div>
                                        <h2 class="text-md text-gray-400">Phone Number</h2>
                                        <h2 class="text-md">{{ $order?->phone_number ?? 'NULL' }}</h2>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <div>
                                        <h2 class="text-md text-gray-400">Date of Birth</h2>
                                        <h2 class="text-md">{{ $order?->date_of_birth ?? 'NULL' }}</h2>
                                    </div>
                                    <div>
                                        <h2 class="text-md text-gray-400">Addresses</h2>
                                        @if (count($order?->addresses ?? []) > 0)
                                            <button id="multiLevelDropdownButton" data-dropdown-toggle="multi-dropdown"
                                                data-dropdown-placement="left"
                                                class="w-full inline-flex items-center px-4 py-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:outline-none"
                                                type="button">
                                                <svg class="w-2.5 h-2.5 me-3" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 6 10">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                                                </svg>
                                                <span>Addresses</span>
                                            </button>

                                            <!-- Dropdown menu -->
                                            <div id="multi-dropdown"
                                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                                    aria-labelledby="multiLevelDropdownButton">

                                                    <li>
                                                        <button
                                                            id="doubleDropdownButton-{{ $order?->defaultAddress()->id }}"
                                                            data-dropdown-toggle="doubleDropdown-{{ $order?->defaultAddress()->id }}"
                                                            data-dropdown-placement="right-start" type="button"
                                                            class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100">
                                                            <span class="text-orange-400">
                                                                {{ $order?->defaultAddress()->title }} - DEFAULT
                                                            </span>
                                                            <svg class="w-2.5 h-2.5 ms-3 rtl:rotate-180"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 6 10">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="m1 9 4-4-4-4" />
                                                            </svg>
                                                        </button>
                                                        <div id="doubleDropdown-{{ $order?->defaultAddress()->id }}"
                                                            class="z-10 hidden bg-white divide-y divide-gray-100 border-2 border-gray-300 shadow min-w-44 dark:bg-gray-700">
                                                            <ul class="p-2 text-sm text-gray-700 dark:text-gray-200 space-y-2"
                                                                aria-labelledby="doubleDropdownButton-{{ $order?->defaultAddress()->id }}">
                                                                {{-- <li>
                                                              <a href="#"
                                                                  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Overview</a>
                                                          </li> --}}
                                                                <p class="font-roboto font-thin text-black text-md">
                                                                    {{ $order?->defaultAddress()->neighborhood }}
                                                                </p>
                                                                <p class="font-roboto font-thin text-black text-md">
                                                                    {{ $order?->defaultAddress()->address_line }}
                                                                </p>
                                                                <p class="text-xl text-nowrap">
                                                                    {{ $order?->defaultAddress()->district }}
                                                                    /
                                                                    {{ $order?->defaultAddress()->city }}</p>
                                                            </ul>
                                                        </div>
                                                    </li>

                                                    @foreach ($order?->addressesWithoutDefaultOne() ?? [] as $address)
                                                        <li wire:key='address-{{ $address->id }}'>
                                                            <button id="doubleDropdownButton-{{ $address->id }}"
                                                                data-dropdown-toggle="doubleDropdown-{{ $address->id }}"
                                                                data-dropdown-placement="right-start" type="button"
                                                                class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100">
                                                                <span>{{ $address->title }}</span>
                                                                <svg class="w-2.5 h-2.5 ms-3 rtl:rotate-180"
                                                                    aria-hidden="true"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 6 10">
                                                                    <path stroke="currentColor" stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="m1 9 4-4-4-4" />
                                                                </svg>
                                                            </button>
                                                            <div id="doubleDropdown-{{ $address->id }}"
                                                                class="z-10 hidden bg-white divide-y divide-gray-100 border-2 border-gray-300 shadow min-w-44 dark:bg-gray-700">
                                                                <ul class="p-2 text-sm text-gray-700 dark:text-gray-200 space-y-2"
                                                                    aria-labelledby="doubleDropdownButton-{{ $address->id }}">
                                                                    {{-- <li>
                                                                    <a href="#"
                                                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Overview</a>
                                                                </li> --}}
                                                                    <p
                                                                        class="font-roboto font-thin text-black text-md">
                                                                        {{ $address->neighborhood }}</p>
                                                                    <p
                                                                        class="font-roboto font-thin text-black text-md">
                                                                        {{ $address->address_line }}</p>
                                                                    <p class="text-xl text-nowrap">
                                                                        {{ $address->district }}
                                                                        /
                                                                        {{ $address->city }}</p>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    @endforeach

                                                </ul>
                                            </div>
                                        @else
                                            <h2 class="text-md">NULL</h2>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gray-200 text-gray-600 p-3 grid grid-cols-2 mb-4 rounded-t-lg border-b-2 pb-3 border-b-gray-200">
                            <div>
                                <div class="grid grid-cols-2">
                                    <h1 class="font-medium">Sipariş No</h1>
                                    <h1 class="font-thin">{{ $order?->id }}</h1>
                                </div>
                                <div class="grid grid-cols-2">
                                    <h1 class="font-medium">Tarih</h1>
                                    <h1 class="font-thin">{{ $order?->created_at->toDayDateTimeString() }}</h1>
                                </div>
                                <div class="grid grid-cols-2">
                                    <h1 class="font-medium">Sipariş Durumu</h1>
                                    <h1 class="font-thin">{{ $order?->status }}</h1>
                                </div>
                            </div>
                            <div>
                                <div class="grid grid-cols-2">
                                    <h1 class="font-medium">Alıcı</h1>
                                    <h1 class="font-thin">{{ $order?->user->full_name() }}</h1>
                                </div>
                                <div class="grid grid-cols-2">
                                    <h1 class="font-medium">Adres</h1>
                                    <h1 class="font-thin">{{ $order?->district }} / {{ $order?->city }}</h1>
                                </div>
                                <div class="grid grid-cols-2">
                                    <h1 class="font-medium">Toplam</h1>
                                    <h1 class="font-thin">{{ App\Helpers::formatPrice($order?->subtotal()) }} TL</h1>
                                </div>
                            </div>
                        </div>
                        @foreach ($order?->items ?? [] as $item)
                            <div class="flex items-start justify-between gap-6 mb-2">
                                <div class="w-9/12 flex items-start justify-between gap-4">
                                    <div class="flex gap-8 items-start ">
                                        <a
                                            href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}">
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                class="w-16 h-auto" alt="">
                                        </a>
                                        <p class="flex items-center gap-2">
                                            <span>{{ $item->quantity }}</span>
                                            <svg class="mt-2 h-5 w-5" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18 17.94 6M18 18 6.06 6" />
                                            </svg>
                                            <a class="hover:underline"
                                                href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}">{{ $item->product->name }}</a>
                                        </p>
                                    </div>
                                    <p class="">{{ App\Helpers::formatPrice($item->item_total_price) }} TL</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
