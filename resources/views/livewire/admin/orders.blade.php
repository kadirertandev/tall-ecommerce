<div>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-(--breakpoint-2xl)">
            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div class="flex flex-row items-end justify-between px-4 lg:space-y-0 lg:space-x-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 ">
                            <h5>
                                <span class="text-gray-500">All Orders:</span>
                                <span class="">{{ $this->orders->total() }}</span>
                            </h5>
                        </div>

                        <x-search-bar />

                    </div>
                    <div class="flex flex-col items-end">
                        {{-- show x entries --}}
                        <x-entry-per-page-dropdown />
                        {{-- show x entries --}}

                        <div class="flex flex-col gap-2 shrink-0 md:flex-row md:items-center lg:justify-end">


                            <x-dropdown-filter dropdownPlacement="left">
                                <x-slot:footer>
                                    <h2 id="accordion-collapse-heading-1">
                                        <button type="button"
                                            class="flex items-center justify-between w-full gap-3 p-2 font-medium text-gray-500 bg-white border-b-2 border-b-gray-300 rtl:text-right dark:text-gray-400"
                                            data-accordion-target="#accordion-collapse-body-1"
                                            aria-controls="accordion-collapse-body-1">
                                            <span>Status</span>
                                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 10 6">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-1" class="hidden"
                                        aria-labelledby="accordion-collapse-heading-1" wire:ignore.self>
                                        <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                                            @foreach (App\Enums\OrderStatusType::cases() as $key => $status)
                                                <li wire:key='filter-status-{{ $key }}'
                                                    class="flex items-center">
                                                    <input wire:model.live='statusFilter'
                                                        id="filter-status-{{ $key . '-' . $status->name }}"
                                                        type="checkbox" value="{{ $status->value }}"
                                                        class="w-4 h-4 bg-gray-100 border-gray-300 rounded-sm text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                                    <label for="filter-status-{{ $key . '-' . $status->name }}"
                                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $status->value }}
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </x-slot:footer>
                            </x-dropdown-filter>


                        </div>

                    </div>

                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                @foreach ($columns as $key => $value)
                                    <th wire:key='heading-{{ $key }}-{{ $value }}'
                                        wire:click='setSortBy("{{ $key }}")' scope="col" class="px-4 py-3">
                                        <x-sort-buttons :column="$key" :title="$value" :sortBy="$this->orderByColumn"
                                            :sortDir="$this->orderByDirection" />
                                    </th>
                                @endforeach
                                <th scope="col" class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->orders as $order)
                                <tr wire:key='order-tr-{{ $order->id }}' @class([
                                    'border-b! border-gray-600!',
                                    'hover:bg-gray-100!' => !$order->deleted_at,
                                    'bg-red-100! hover:bg-red-200!' => $order->deleted_at,
                                ])>
                                    <th scope="row"
                                        class="flex items-center gap-2 px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        @if ($order->user->profile_image ?? false)
                                            <img src="{{ asset('storage/' . $order->user->profile_image) }}"
                                                class="w-10 h-auto rounded-md">
                                        @else
                                            <img src="{{ asset('storage/profile-customer.png') }}"
                                                class="w-10 h-auto rounded-md">
                                        @endif
                                        {{ $order->customer_name }}
                                    </th>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $order->city }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $order->district }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $order->neighborhood }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $order->address_line }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ App\Helpers::formatPrice($order->subtotal) }} TL</td>
                                    @can('edit orders')
                                        <td class="px-4 py-2 font-medium text-gray-900 cursor-pointer whitespace-nowrap dark:text-white"
                                            id="orderStatusDropdownButton-{{ $order->id }}"
                                            data-dropdown-placement="left"
                                            data-dropdown-toggle="orderStatusDropdown-{{ $order->id }}">
                                            <div class="flex items-center">
                                                <svg class="w-2.5 h-2.5 me-3" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                                                </svg>
                                                <span>{{ $order->status }}</span>
                                            </div>

                                            <!-- Dropdown menu -->
                                            <div id="orderStatusDropdown-{{ $order->id }}" style="z-index: 7777"
                                                class="hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                                    aria-labelledby="orderStatusDropdownButton-{{ $order->id }}">
                                                    @foreach (App\Enums\OrderStatusType::cases() as $key => $status)
                                                        <li wire:key='{{ $order->id }}-{{ $status->name }}'
                                                            wire:click='changeStatus({{ $order->id }}, "{{ $status->value }}")'
                                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                            {{ $status->value }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </td>
                                    @else
                                        <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $order->status }}</td>
                                    @endcan
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $order->created_at }}</td>

                                    <td class="px-4 py-2">
                                        <button id="dropdownMenuIconButton-{{ $order->id }}"
                                            data-dropdown-toggle="dropdownDots-{{ $order->id }}"
                                            data-dropdown-placement="left"
                                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-300 focus:ring-2 focus:outline-hidden dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                            type="button">
                                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 4 15">
                                                <path
                                                    d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown menu -->
                                        <div id="dropdownDots-{{ $order->id }}" style="z-index: 8888"
                                            class="hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 *:cursor-pointer"
                                                aria-labelledby="dropdownMenuIconButton-{{ $order->id }}">
                                                <li wire:click='showViewModal({{ $order->id }})'
                                                    class="flex items-center gap-1 px-4 py-2 hover:bg-gray-100">
                                                    <svg class="w-4 h-4 text-gray-800 dark:text-white"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-width="2"
                                                            d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                                        <path stroke="currentColor" stroke-width="2"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>

                                                    <span>View</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-b border-gray-600 hover:bg-gray-100">
                                    <td colspan="9" class="text-2xl text-center text-thin">No orders
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <nav class="p-2">
                    {{ $this->orders->links() }}
                </nav>
            </div>
        </div>
    </section>

    <x-modals.admin.order.view-modal :order="$selectedOrder" />

</div>
