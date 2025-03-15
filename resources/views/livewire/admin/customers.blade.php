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

                        <x-admin.search-bar />

                    </div>
                    <div class="flex flex-col items-end">
                        {{-- show x entries --}}
                        <x-admin.entry-per-page-dropdown />
                        {{-- show x entries --}}
                        <div class="flex flex-col gap-2 shrink-0 md:flex-row md:items-center lg:justify-end">
                            <x-admin.dropdown-filter>
                                @can('force delete customers')
                                    <x-slot:toggles>
                                        <x-admin.toggle toggle="withDeleteRequest" text="With Delete Request" />
                                        <x-admin.toggle toggle="onlyDeleteRequest" text="Only Delete Request" />
                                    </x-slot:toggles>
                                @endcan
                            </x-admin.dropdown-filter>
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
                                        <x-admin.sort-buttons :column="$key" :title="$value" :sortBy="$this->sortBy"
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
                                                @can('edit customers')
                                                    <li wire:click='showEditModal({{ $customer->id }})'
                                                        class="flex items-center gap-1 px-4 py-2 hover:bg-cyan-500 group hover:text-white">
                                                        <svg class="w-4 h-4 text-gray-800 dark:text-white group-hover:text-white"
                                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                                        </svg>
                                                        <span>Edit</span>
                                                    </li>
                                                @endcan
                                                @if ($customer->delete_request)
                                                    @can('force delete customers')
                                                        <li wire:click="delete({{ $customer->id }})"
                                                            class="flex items-center gap-1 px-4 py-2 hover:bg-red-800 group hover:text-white">
                                                            <svg class="w-4 h-4 text-gray-800 dark:text-white group-hover:text-white"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                            </svg>
                                                            <span>Delete</span>
                                                        </li>
                                                    @endcan
                                                @endif
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

    {{-- modals --}}
    {{-- modals --}}
    <x-admin.customer.view-modal :customer="$selectedCustomer"></x-admin.customer.view-modal>
    {{-- <x-admin.admin.edit-modal :admin="$selectedAdmin" :editForm="$this->editForm" :roles="$this->roles()"></x-admin.admin.edit-modal> --}}

</div>
