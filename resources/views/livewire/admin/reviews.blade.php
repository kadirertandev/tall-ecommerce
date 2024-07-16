<div>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-screen-2xl">
            <div class="relative {{-- overflow-hidden --}} bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div
                    class="{{-- bg-purple-300 --}} flex px-4 {{-- py-3 --}} {{-- space-y-3 --}} flex-row items-end justify-between lg:space-y-0 lg:space-x-4">
                    <div class="flex-1 {{-- bg-red-400 --}}">
                        <div class="flex items-center space-x-4 ">
                            <h5>
                                <span class="text-gray-500">All Reviews:</span>
                                <span class="">{{ $this->reviews->total() }}</span>
                            </h5>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="relative block w-80">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search icon</span>
                                </div>
                                <input wire:model.live.debounce.300ms='keyword' type="text" id="search-navbar"
                                    class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search...">
                                <button wire:click="$set('keyword','')" @class([
                                    'absolute inset-y-0 rtl:inset-r-0 end-0 flex items-center pe-3',
                                    'hidden' => strlen($this->keyword) <= 0,
                                ]) type="button">
                                    <svg class="w-4 h-4 text-red-500 hover:w-5 hover:h-5" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill=""
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="flex flex-col items-end">
                        <h5 class="flex items-center gap-1">
                            <span class="text-gray-500">Show</span>

                            <button id="dropdownDefaultButton123" data-dropdown-toggle="dropdown123"
                                class="text-gray-900 bg-gray-50 border border-gray-300 focus:ring-2 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm p-1 gap-4 text-center inline-flex items-center "
                                type="button">{{ $this->perPage }} <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            <!-- per page dropdown menu -->
                            <div id="dropdown123"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700"
                                {{-- wire:ignore.self --}}>
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 *:cursor-pointer"
                                    aria-labelledby="dropdownDefaultButton123">
                                    @foreach ([5, 10, 25, 50, 100] as $perPage)
                                        <li @click="$wire.set('perPage',{{ $perPage }})"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 ">
                                            {{ $perPage }}
                                        </li>
                                    @endforeach
                                    <li class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 ">
                                        <input wire:model.live.debounce.300ms='perPage' type="number" min="5"
                                            value="{{ $this->perPage }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    </li>
                                </ul>
                            </div>

                            <span>entries</span>
                        </h5>
                        <div class="flex flex-col flex-shrink-0 md:flex-row md:items-center lg:justify-end gap-2">

                            <div class="flex items-center justify-center">
                                <button id="dropdownDefault" data-dropdown-toggle="dropdown"
                                    class="bg-teal-500 flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-teal-600 focus:ring-4 focus:ring-primary-300 focus:outline-none"
                                    type="button">
                                    Filter
                                    <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown menu -->
                                <div id="dropdown" class="z-10 hidden w-56 p-2 bg-white rounded-lg shadow"
                                    {{-- wire:ignore.self --}}>
                                    <div id="accordion-collapse" data-accordion="collapse">
                                        @can('force delete admins')
                                            <label class="inline-flex items-center w-full p-2 cursor-pointer">
                                                <input wire:model.live="withTrashed" type="checkbox" class="sr-only peer">
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600">
                                                </div>
                                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">With
                                                    Trashed</span>
                                            </label>
                                            <label class="inline-flex items-center w-full p-2 cursor-pointer">
                                                <input wire:model.live="onlyTrashed" type="checkbox" class="sr-only peer">
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600">
                                                </div>
                                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Only
                                                    Trashed</span>
                                            </label>
                                        @endcan
                                        <h2 id="accordion-collapse-heading-1">
                                            <button type="button"
                                                class="flex items-center justify-between w-full p-2 font-medium rtl:text-right text-gray-500 bg-white dark:text-gray-400  gap-3"
                                                data-accordion-target="#accordion-collapse-body-1"
                                                {{-- aria-expanded="true" --}} aria-controls="accordion-collapse-body-1">
                                                <span>Status</span>
                                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="accordion-collapse-body-1" class="hidden"
                                            aria-labelledby="accordion-collapse-heading-1" wire:ignore.self>
                                            <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                                                @foreach (App\Enums\ReviewStatusType::cases() as $key => $status)
                                                    <li wire:key='filter-status-{{ $key }}'
                                                        class="flex items-center">
                                                        <input wire:model.live='statusFilter'
                                                            id="filter-status-{{ $key . '-' . $status->name }}"
                                                            type="checkbox" value="{{ $status->value }}"
                                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                                        <label for="filter-status-{{ $key . '-' . $status->name }}"
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ Str::headline($status->value) }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                {{-- <th scope="col" class="px-4 py-3">admin</th>
                          <th scope="col" class="px-4 py-3">Is Popular</th>
                          <th scope="col" class="px-4 py-3">Updated At</th> --}}
                                @foreach ($this->columns as $key => $value)
                                    <th wire:key='heading-{{ $key }}-{{ $value }}'
                                        wire:click='setSortBy("{{ $key }}")' scope="col"
                                        class="px-4 py-3">
                                        <x-admin.sort-buttons :column="$key" :title="$value" :sortBy="$this->sortBy"
                                            :sortDir="$this->sortDir" />
                                    </th>
                                @endforeach
                                <th scope="col" class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->reviews as $review)
                                <tr wire:key='admin-tr-{{ $review->id }}' @class([
                                    'border-b border-gray-600',
                                    'hover:bg-gray-100' => !$review->deleted_at,
                                    'bg-red-100 hover:bg-red-200' => $review->deleted_at,
                                ])>
                                    <th scope="row"
                                        class="flex items-center gap-2 px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        @if ($review->user->profile_image ?? false)
                                            <img src="{{ asset('storage/' . $review->user->profile_image) }}"
                                                class="w-10 h-auto rounded-md">
                                        @else
                                            <img src="{{ asset('storage/profile-admin.png') }}"
                                                class="w-10 h-auto rounded-md">
                                        @endif
                                        {{ $review->user->full_name() }}
                                    </th>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <img src="{{ asset('storage/' . $review->product->image) }}"
                                            class="w-10 h-auto rounded-md">
                                    </td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $review->title }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ Str::limit($review->comment, 30) }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center">
                                            <x-stars :stars="$review->rating" />
                                        </div>
                                    </td>
                                    @if (auth()->user()->can('edit reviews') && !$review->deleted_at)
                                        <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap cursor-pointer"
                                            id="reviewStatusDropdownButton-{{ $review->id }}"
                                            data-dropdown-placement="left"
                                            data-dropdown-toggle="reviewStatusDropdown-{{ $review->id }}">
                                            <div @class([
                                                'flex items-center rounded-lg p-2',
                                                'bg-teal-400' => $review->status === App\Enums\ReviewStatusType::APPROVED,
                                                'bg-orange-400' =>
                                                    $review->status === App\Enums\ReviewStatusType::EVALUATING,
                                                'bg-red-400' => $review->status === App\Enums\ReviewStatusType::REJECTED,
                                            ])>
                                                <svg class="w-2.5 h-2.5 me-3" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 6 10">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                                                </svg>
                                                <span>{{ Str::headline($review->status->value) }}</span>
                                            </div>

                                            <!-- Dropdown menu -->
                                            <div id="reviewStatusDropdown-{{ $review->id }}" style="z-index: 7777"
                                                class="hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                                    aria-labelledby="reviewStatusDropdownButton-{{ $review->id }}">
                                                    @foreach (App\Enums\ReviewStatusType::cases() as $key => $status)
                                                        <li wire:key='{{ $review->id }}-{{ $status->name }}'
                                                            wire:click='changeStatus({{ $review->id }}, "{{ $status->value }}")'
                                                            @class(['block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600'])>
                                                            {{ Str::headline($status->value) }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </td>
                                    @else
                                        <td
                                            class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ Str::headline($review->status->value) }}
                                        </td>
                                    @endcan
                                    <td
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $review->created_at }}</td>
                                    <td class="px-4 py-2">
                                        <button id="dropdownMenuIconButton-{{ $review->id }}"
                                            data-dropdown-toggle="dropdownDots-{{ $review->id }}"
                                            data-dropdown-placement="left"
                                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-300 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                            type="button">
                                            <svg class="w-5 h-5" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 4 15">
                                                <path
                                                    d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown menu -->
                                        <div id="dropdownDots-{{ $review->id }}" style="z-index: 8888"
                                            class="hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 *:cursor-pointer"
                                                aria-labelledby="dropdownMenuIconButton-{{ $review->id }}">
                                                <li wire:click='showViewModal({{ $review->id }})'
                                                    class="px-4 py-2 hover:bg-gray-100 flex items-center gap-1">
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
                                                @can('edit reviews')
                                                    <li wire:click='showEditModal({{ $review->id }})'
                                                        class="px-4 py-2 hover:bg-cyan-500 group hover:text-white flex items-center gap-1">
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
                                                @if (!$review->deleted_at)
                                                    @if ($review->status == App\Enums\ReviewStatusType::REJECTED)
                                                        @can('delete reviews')
                                                            @if ($review->isNot(auth()->user()))
                                                                <li @click="$dispatch('delete-review-modal',{reviewId: {{ $review->id }}})"
                                                                    class="px-4 py-2 hover:bg-red-500 group hover:text-white flex items-center gap-1">
                                                                    <svg class="w-4 h-4 text-gray-800 dark:text-white group-hover:text-white"
                                                                        aria-hidden="true"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24">
                                                                        <path stroke="currentColor"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                                    </svg>
                                                                    <span>Delete</span>
                                                                </li>
                                                            @endif
                                                        @endcan
                                                    @endif
                                                @else
                                                    @can('force delete reviews')
                                                        <li wire:click='restore({{ $review->id }})'
                                                            class="px-4 py-2 hover:bg-green-500 group hover:text-white flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="w-4 h-4 text-gray-800   group-hover:fill-white"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6a7 7 0 0 1 7-7a7 7 0 0 1 7 7a7 7 0 0 1-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.9 8.9 0 0 0 13 21a9 9 0 0 0 9-9a9 9 0 0 0-9-9" />
                                                            </svg>
                                                            <span>Restore</span>
                                                        </li>
                                                        <li @click="$dispatch('force-delete-review-modal',{reviewId: {{ $review->id }}})"
                                                            class="px-4 py-2 hover:bg-red-800 group hover:text-white flex items-center gap-1">
                                                            <svg class="w-4 h-4 text-gray-800 dark:text-white group-hover:text-white"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                            </svg>
                                                            <span>Force Delete</span>
                                                        </li>
                                                    @endcan
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                            </tr>
                        @empty
                            <tr class="border-b border-gray-600 hover:bg-gray-100">
                                <td colspan="9" class="text-2xl text-thin text-center">No reviews
                                    found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <nav class="p-2">
                {{ $this->reviews->links() }}
            </nav>
        </div>
    </div>
</section>

{{-- modals --}}
<x-admin.review.view-modal :review="$selectedReview"></x-review.review.view-modal>
    <x-admin.review.edit-modal :review="$selectedReview" :editForm="$this->editForm"></x-admin.review.edit-modal>
    {{-- modals --}}

</div>
@script
<script>
    Livewire.on("refresh-flowbite", function() {
        setTimeout(() => {
            initFlowbite();
            console.log("flowbite initialized")
        }, 300);
    })

    Livewire.on("open-review-edit-modal-referred", function() {
        setTimeout(() => {
            Livewire.dispatch("open-review-edit-modal");
        }, 300)
    })
</script>
@endscript
