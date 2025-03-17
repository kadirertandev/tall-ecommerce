<div>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-(--breakpoint-2xl)">
            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div class="flex flex-row items-end justify-between px-4 lg:space-y-0 lg:space-x-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4">
                            <h5>
                                <span class="text-gray-500">All Categories:</span>
                                <span class="">{{ $this->categories->total() }}</span>
                            </h5>
                        </div>

                        <x-search-bar />

                    </div>
                    <div class="flex flex-col items-end">
                        {{-- show x entries --}}
                        <x-entry-per-page-dropdown />
                        {{-- show x entries --}}

                        <div class="flex flex-col gap-2 shrink-0 md:flex-row md:items-center lg:justify-end">
                            {{-- dropdown-filter start --}}
                            <x-dropdown-filter>

                                <x-slot:toggles>
                                    @can('force delete categories')
                                        <x-toggle toggle="withTrashed" text="With Trashed" />
                                        <x-toggle toggle="onlyTrashed" text="Only Trashed" />
                                    @endcan
                                </x-slot:toggles>
                                <x-slot:footer>
                                    <x-toggle toggle="onlyPopular" text="Only Popular" />
                                </x-slot:footer>
                            </x-dropdown-filter>
                            {{-- dropdown-filter end --}}

                            @can('create categories')
                                <button @click="$dispatch('open-modal', {name: 'create-category'})" type="button"
                                    class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 focus:ring-2 focus:ring-primary-300 focus:outline-hidden">
                                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                    </svg>
                                    Add new category
                                </button>
                            @endcan
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
                            @forelse ($this->categories as $category)
                                <tr wire:key='category-tr-{{ $category->id }}' @class([
                                    'border-b! border-gray-600!',
                                    'hover:bg-gray-100!' => !$category->deleted_at,
                                    'bg-red-100! hover:bg-red-200!' => $category->deleted_at,
                                ])>
                                    <th scope="row"
                                        class="flex items-center px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="iMac Front Image"
                                            class="w-10 h-auto mr-3">
                                        {{ $category->name }}
                                    </th>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $category->is_popular }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $category->updated_at }}</td>
                                    <td class="px-4 py-2">
                                        <button id="dropdownMenuIconButton-{{ $category->id }}"
                                            data-dropdown-toggle="dropdownDots-{{ $category->id }}"
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
                                        <div id="dropdownDots-{{ $category->id }}" style="z-index: 8888"
                                            class="hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 *:cursor-pointer"
                                                aria-labelledby="dropdownMenuIconButton-{{ $category->id }}">
                                                <li wire:click='showViewModal({{ $category->id }})'
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
                                                @can('edit categories')
                                                    <li wire:click='showEditModal({{ $category->id }})'
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
                                                @if (!$category->deleted_at)
                                                    @can('delete categories')
                                                        <li wire:click="askDeleteCategory({{ $category->id }})"
                                                            class="flex items-center gap-1 px-4 py-2 hover:bg-red-500 group hover:text-white">
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
                                                @else
                                                    @can('force delete categories')
                                                        <li wire:click='restore({{ $category->id }})'
                                                            class="flex items-center gap-1 px-4 py-2 hover:bg-green-500 group hover:text-white">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="w-4 h-4 text-gray-800 group-hover:fill-white"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6a7 7 0 0 1 7-7a7 7 0 0 1 7 7a7 7 0 0 1-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.9 8.9 0 0 0 13 21a9 9 0 0 0 9-9a9 9 0 0 0-9-9" />
                                                            </svg>
                                                            <span>Restore</span>
                                                        </li>
                                                        <li wire:click="askDeleteCategory({{ $category->id }}, {{ true }})"
                                                            class="flex items-center gap-1 px-4 py-2 hover:bg-red-800 group hover:text-white">
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
                                    <td colspan="9" class="text-2xl text-center text-thin">No categories
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <nav class="p-2">
                    {{ $this->categories->links() }}
                </nav>
            </div>
        </div>
    </section>

    <x-modals.admin.category.create-modal />
    <x-modals.admin.category.view-modal :category="$selectedCategory" />
    <x-modals.admin.category.edit-modal :category="$selectedCategory" :brands="$this->editForm->brands()" :editform="$this->editForm" />

</div>
