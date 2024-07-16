@props(['customer'])
<div x-data="{ show: false }" x-show="show" x-on:open-customer-view-modal.window="show = true"
    x-on:close-customer-view-modal.window="show = false,$dispatch('customer-view-modal-closed')" class="relative z-10"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" wire:ignore.self style="display: none;">
    <div class="fixed inset-0 bg-gray-500 opacity-40 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                {{-- modal content --}}
                <div class="bg-white" @click.outside="$dispatch('close-customer-view-modal')">
                    {{-- header --}}
                    <div class="flex items-center justify-between p-4 border-b  dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $customer?->full_name() }}
                        </h3>

                        <button type="button" @click="$dispatch('close-customer-view-modal')"
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
                    <div class="p-4 md:p-5 space-y-2">

                        <div class="mb-5 flex gap-2 items-start">
                            @if ($customer->profile_image ?? false)
                                <img src="{{ asset('storage/' . $customer->profile_image) }}"
                                    class="w-24 h-auto rounded-md">
                            @else
                                <img src="{{ asset('storage/profile-customer.png') }}" class="w-24 h-auto rounded-md">
                            @endif

                            <div>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <div>
                                        <h2 class="text-md text-gray-400">First Name</h2>
                                        <h2 class="text-md">{{ $customer?->first_name }}</h2>
                                    </div>
                                    <div>
                                        <h2 class="text-md text-gray-400">Last Name</h2>
                                        <h2 class="text-md">{{ $customer?->last_name }}</h2>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <div>
                                        <h2 class="text-md text-gray-400">Email</h2>
                                        <h2 class="text-md">{{ $customer?->email }}</h2>
                                    </div>
                                    <div>
                                        <h2 class="text-md text-gray-400">Phone Number</h2>
                                        <h2 class="text-md">{{ $customer?->phone_number ?? 'NULL' }}</h2>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <div>
                                        <h2 class="text-md text-gray-400">Date of Birth</h2>
                                        <h2 class="text-md">{{ $customer?->date_of_birth ?? 'NULL' }}</h2>
                                    </div>
                                    <div>
                                        <h2 class="text-md text-gray-400">Addresses</h2>
                                        @if (count($customer?->addresses ?? []) > 0)
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
                                                            id="doubleDropdownButton-{{ $customer?->defaultAddress()->id }}"
                                                            data-dropdown-toggle="doubleDropdown-{{ $customer?->defaultAddress()->id }}"
                                                            data-dropdown-placement="right-start" type="button"
                                                            class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100">
                                                            <span class="text-orange-400">
                                                                {{ $customer?->defaultAddress()->title }} - DEFAULT
                                                            </span>
                                                            <svg class="w-2.5 h-2.5 ms-3 rtl:rotate-180"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 6 10">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="m1 9 4-4-4-4" />
                                                            </svg>
                                                        </button>
                                                        <div id="doubleDropdown-{{ $customer?->defaultAddress()->id }}"
                                                            class="z-10 hidden bg-white divide-y divide-gray-100 border-2 border-gray-300 shadow min-w-44 dark:bg-gray-700">
                                                            <ul class="p-2 text-sm text-gray-700 dark:text-gray-200 space-y-2"
                                                                aria-labelledby="doubleDropdownButton-{{ $customer?->defaultAddress()->id }}">
                                                                {{-- <li>
                                                              <a href="#"
                                                                  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Overview</a>
                                                          </li> --}}
                                                                <p class="font-roboto font-thin text-black text-md">
                                                                    {{ $customer?->defaultAddress()->neighborhood }}
                                                                </p>
                                                                <p class="font-roboto font-thin text-black text-md">
                                                                    {{ $customer?->defaultAddress()->address_line }}
                                                                </p>
                                                                <p class="text-xl text-nowrap">
                                                                    {{ $customer?->defaultAddress()->district }}
                                                                    /
                                                                    {{ $customer?->defaultAddress()->city }}</p>
                                                            </ul>
                                                        </div>
                                                    </li>

                                                    @foreach ($customer?->addressesWithoutDefaultOne() ?? [] as $address)
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

                        @if ($customer?->deleted_at)
                            <div class="mb-5 flex items-center gap-4">
                                <div>
                                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted At</h1>
                                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                                        {{ $customer?->deleted_at->toDateTimeString() }}</p>
                                </div>
                                <div>
                                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted By</h1>
                                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                                        {{ $customer?->deletedBy?->full_name() ?? 'NULL' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
