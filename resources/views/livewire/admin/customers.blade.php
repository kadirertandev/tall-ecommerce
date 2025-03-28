<div>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-(--breakpoint-2xl)">
            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div class="flex flex-row items-end justify-between px-4 lg:space-y-0 lg:space-x-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 ">
                            <h5>
                                <span class="text-gray-500">All Customers:</span>
                                <span class="">{{ $this->customers->total() }}</span>
                            </h5>
                        </div>

                        <x-search-bar />

                    </div>
                    <div class="flex flex-col items-end">
                        {{-- show x entries --}}
                        <x-entry-per-page-dropdown />
                        {{-- show x entries --}}
                        <div class="flex flex-col gap-2 shrink-0 md:flex-row md:items-center lg:justify-end">
                            <x-dropdown-filter>
                                @can('force delete customers')
                                    <x-slot:toggles>
                                        <x-toggle toggle="onlyDeleteRequest" text="Only Delete Request" />
                                    </x-slot:toggles>
                                @endcan
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
                                        <x-sort-buttons :column="$key" :title="$value" :sortBy="$this->sortBy"
                                            :sortDir="$this->sortDir" />
                                    </th>
                                @endforeach
                                <th scope="col" class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->customers as $customer)
                                <tr wire:key='admin-tr-{{ $customer->id }}' @class([
                                    'border-b! border-gray-600!',
                                    'hover:bg-gray-100!' => !$customer->deleted_at,
                                    'bg-red-100! hover:bg-red-200!' => $customer->deleted_at,
                                ])>
                                    <th scope="row"
                                        class="flex items-center gap-2 px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        @if ($customer->profile_image ?? false)
                                            <img src="{{ asset('storage/' . $customer->profile_image) }}"
                                                class="w-10 h-auto rounded-md">
                                        @else
                                            <img src="{{ asset('storage/profile-customer.png') }}"
                                                class="w-10 h-auto rounded-md">
                                        @endif
                                        {{ $customer->full_name() }}
                                    </th>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $customer->email }}</td>
                                    <td @class([
                                        'px-4 py-2 whitespace-nowrap dark:text-white',
                                        'text-gray-900 font-medium' => $customer->email_verified_at,
                                        'bg-red-400 text-white font-bold' => !$customer->email_verified_at,
                                    ])>
                                        {{ $customer->email_verified_at ?? 'NOT VERIFIED' }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $customer->phone_number ?? 'NULL' }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $customer->created_at ?? 'NULL' }}</td>
                                    <td class="px-4 py-2  font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center justify-center">
                                            @if ($customer->delete_request)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                    viewBox="0 0 32 32">
                                                    <g fill="none">
                                                        <path fill="#00d26a"
                                                            d="M2 6a4 4 0 0 1 4-4h20a4 4 0 0 1 4 4v20a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4z" />
                                                        <path fill="#f4f4f4"
                                                            d="M13.242 23c-.383 0-.766-.143-1.059-.43l-5.744-5.642a1.453 1.453 0 0 1 0-2.08a1.517 1.517 0 0 1 2.118 0l4.685 4.601L23.443 9.431a1.517 1.517 0 0 1 2.118 0a1.45 1.45 0 0 1 0 2.08l-11.26 11.058a1.5 1.5 0 0 1-1.059.431" />
                                                    </g>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 128 128">
                                                    <path fill="#fff"
                                                        d="M109.54 93.9a3.573 3.573 0 0 1 0 5.03l-10.6 10.61a3.573 3.573 0 0 1-5.03 0L64 79.64l-29.9 29.9a3.573 3.573 0 0 1-5.03 0L18.46 98.93a3.573 3.573 0 0 1 0-5.03L48.36 64l-29.9-29.9a3.585 3.585 0 0 1 0-5.04l10.61-10.6a3.555 3.555 0 0 1 5.03 0L64 48.36l29.9-29.9a3.555 3.555 0 0 1 5.03 0l10.61 10.6a3.585 3.585 0 0 1 0 5.04L79.64 64z" />
                                                    <path fill="#ff370e"
                                                        d="M123.83.47H4.17a3.71 3.71 0 0 0-3.7 3.7v119.66c0 2.04 1.66 3.7 3.7 3.7h119.66c2.03 0 3.69-1.66 3.69-3.7V4.17a3.69 3.69 0 0 0-3.69-3.7M109.54 93.9a3.573 3.573 0 0 1 0 5.03l-10.6 10.61a3.573 3.573 0 0 1-5.03 0L64 79.64l-29.9 29.9a3.573 3.573 0 0 1-5.03 0L18.46 98.93a3.573 3.573 0 0 1 0-5.03L48.36 64l-29.9-29.9a3.585 3.585 0 0 1 0-5.04l10.61-10.6a3.555 3.555 0 0 1 5.03 0L64 48.36l29.9-29.9a3.555 3.555 0 0 1 5.03 0l10.61 10.6a3.585 3.585 0 0 1 0 5.04L79.64 64z" />
                                                </svg>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-2">
                                        <button id="dropdownMenuIconButton-{{ $customer->id }}"
                                            data-dropdown-toggle="dropdownDots-{{ $customer->id }}"
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
                                        <div id="dropdownDots-{{ $customer->id }}" style="z-index: 8888"
                                            class="hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 *:cursor-pointer"
                                                aria-labelledby="dropdownMenuIconButton-{{ $customer->id }}">
                                                <li wire:click='showViewModal({{ $customer->id }})'
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
                                    <td colspan="9" class="text-2xl text-center text-thin">No customers
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <nav class="p-2">
                    {{ $this->customers->links() }}
                </nav>
            </div>
        </div>
    </section>

    <x-modals.admin.customer.view-modal :customer="$selectedCustomer" />

</div>
