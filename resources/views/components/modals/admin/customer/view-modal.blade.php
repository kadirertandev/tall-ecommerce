@props(['customer'])
<x-modal name="view-customer" :title="$customer?->full_name()">
    <div class="p-4 space-y-2 md:p-5">
        <div class="flex items-start gap-2 mb-5">
            @if ($customer->profile_image ?? false)
                <img src="{{ asset('storage/' . $customer->profile_image) }}" class="w-24 h-auto rounded-md">
            @else
                <img src="{{ asset('storage/profile-customer.png') }}" class="w-24 h-auto rounded-md">
            @endif

            <div>
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div>
                        <h2 class="text-gray-400 text-md">First Name</h2>
                        <h2 class="text-md">{{ $customer?->first_name }}</h2>
                    </div>
                    <div>
                        <h2 class="text-gray-400 text-md">Last Name</h2>
                        <h2 class="text-md">{{ $customer?->last_name }}</h2>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div>
                        <h2 class="text-gray-400 text-md">Email</h2>
                        <h2 class="text-md">{{ $customer?->email }}</h2>
                    </div>
                    <div>
                        <h2 class="text-gray-400 text-md">Phone Number</h2>
                        <h2 class="text-md">{{ $customer?->phone_number ?? 'NULL' }}</h2>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div>
                        <h2 class="text-gray-400 text-md">Date of Birth</h2>
                        <h2 class="text-md">{{ $customer?->date_of_birth ?? 'NULL' }}</h2>
                    </div>
                    <div>
                        <h2 class="text-gray-400 text-md">Addresses</h2>
                        @if (count($customer?->addresses ?? []) > 0)
                            <button id="multiLevelDropdownButton" data-dropdown-toggle="multi-dropdown"
                                data-dropdown-placement="left"
                                class="inline-flex items-center w-full px-4 py-2 text-sm font-medium text-center text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:outline-hidden"
                                type="button">
                                <svg class="w-2.5 h-2.5 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 1 1 5l4 4" />
                                </svg>
                                <span>Addresses</span>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="multi-dropdown"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="multiLevelDropdownButton">

                                    <li>
                                        <button id="doubleDropdownButton-{{ $customer?->defaultAddress()->id }}"
                                            data-dropdown-toggle="doubleDropdown-{{ $customer?->defaultAddress()->id }}"
                                            data-dropdown-placement="right-start" type="button"
                                            class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100">
                                            <span class="text-orange-400">
                                                {{ $customer?->defaultAddress()->title }} - DEFAULT
                                            </span>
                                            <svg class="w-2.5 h-2.5 ms-3 rtl:rotate-180" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                                            </svg>
                                        </button>
                                        <div id="doubleDropdown-{{ $customer?->defaultAddress()->id }}"
                                            class="z-10 hidden bg-white border-2 border-gray-300 divide-y divide-gray-100 shadow-sm min-w-44 dark:bg-gray-700">
                                            <ul class="p-2 space-y-2 text-sm text-gray-700 dark:text-gray-200"
                                                aria-labelledby="doubleDropdownButton-{{ $customer?->defaultAddress()->id }}">
                                                {{-- <li>
                                          <a href="#"
                                              class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Overview</a>
                                      </li> --}}
                                                <p class="font-thin text-black font-roboto text-md">
                                                    {{ $customer?->defaultAddress()->neighborhood }}
                                                </p>
                                                <p class="font-thin text-black font-roboto text-md">
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
                                                <svg class="w-2.5 h-2.5 ms-3 rtl:rotate-180" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 6 10">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                                                </svg>
                                            </button>
                                            <div id="doubleDropdown-{{ $address->id }}"
                                                class="z-10 hidden bg-white border-2 border-gray-300 divide-y divide-gray-100 shadow-sm min-w-44 dark:bg-gray-700">
                                                <ul class="p-2 space-y-2 text-sm text-gray-700 dark:text-gray-200"
                                                    aria-labelledby="doubleDropdownButton-{{ $address->id }}">
                                                    {{-- <li>
                                                <a href="#"
                                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Overview</a>
                                            </li> --}}
                                                    <p class="font-thin text-black font-roboto text-md">
                                                        {{ $address->neighborhood }}</p>
                                                    <p class="font-thin text-black font-roboto text-md">
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
            <div class="flex items-center gap-4 mb-5">
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
</x-modal>
