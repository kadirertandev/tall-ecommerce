<div>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-screen-2xl">
            <div class="relative {{-- overflow-hidden --}} bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div
                    class="{{-- bg-purple-300 --}} flex px-4 {{-- py-3 --}} {{-- space-y-3 --}} flex-row items-end justify-between lg:space-y-0 lg:space-x-4">
                    <div class="flex-1 {{-- bg-red-400 --}}">
                        <div class="flex items-center space-x-4 ">
                            <h5>
                                <span class="text-gray-500">All Products:</span>
                                {{-- {{ dd($this->products) }} --}}
                                <span class="">{{ $this->products->total() }}</span>
                            </h5>
                            <h5>
                                <span class="text-gray-500">Total Revenue:</span>
                                <span class="">{{ App\Helpers::formatPrice($this->totalRevenue) }} TL</span>
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
                        <div class=" flex flex-col flex-shrink-0 md:flex-row md:items-center lg:justify-end gap-2">
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
                                    wire:ignore.self>
                                    <div id="accordion-collapse" data-accordion="collapse">
                                        @can('force delete products')
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
                                                class="border-b-2 border-b-gray-300 flex items-center justify-between w-full p-2 font-medium rtl:text-right text-gray-500 bg-white dark:text-gray-400  gap-3"
                                                data-accordion-target="#accordion-collapse-body-1"
                                                {{-- aria-expanded="true" --}} aria-controls="accordion-collapse-body-1">
                                                <span>Category</span>
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
                                            <ul class="space-y-2 text-sm max-h-[200px] overflow-y-scroll"
                                                aria-labelledby="dropdownDefault">
                                                @foreach ($this->categories as $category)
                                                    <li wire:key='filter-category-{{ $category->id }}'
                                                        class="flex items-center">
                                                        <input wire:model.live='categoriesFilter'
                                                            id="{{ $category->name . '-' . $category->id }}"
                                                            type="checkbox" value="{{ $category->id }}"
                                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                                        <label for="{{ $category->name . '-' . $category->id }}"
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $category->name }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <h2 id="accordion-collapse-heading-2">
                                            <button type="button"
                                                class="border-b-2 border-b-gray-300 flex items-center justify-between w-full p-2 font-medium rtl:text-right text-gray-500 bg-white    dark:text-gray-400  gap-3"
                                                data-accordion-target="#accordion-collapse-body-2"
                                                {{-- aria-expanded="true" --}} aria-controls="accordion-collapse-body-2">
                                                <span>Brand</span>
                                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="accordion-collapse-body-2" class="hidden"
                                            aria-labelledby="accordion-collapse-heading-2" wire:ignore.self>
                                            <ul class="space-y-2 text-sm max-h-[200px] overflow-y-scroll"
                                                aria-labelledby="dropdownDefault">
                                                @foreach ($this->brands as $brand)
                                                    <li wire:key='filter-brand-{{ $brand->id }}'
                                                        class="flex items-center">
                                                        <input wire:model.live='brandsFilter'
                                                            id="{{ $brand->name . '-' . $brand->id }}"
                                                            type="checkbox" value="{{ $brand->id }}"
                                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                                        <label for="{{ $brand->name . '-' . $brand->id }}"
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $brand->name }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <h2 id="accordion-collapse-heading-3">
                                            <button type="button"
                                                class="border-b-2 border-b-gray-300 flex items-center justify-between w-full p-2 font-medium rtl:text-right text-gray-500 bg-white    dark:text-gray-400  gap-3"
                                                data-accordion-target="#accordion-collapse-body-3"
                                                {{-- aria-expanded="true" --}} aria-controls="accordion-collapse-body-3">
                                                <span>Price</span>
                                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="accordion-collapse-body-3" class="hidden"
                                            aria-labelledby="accordion-collapse-heading-3" wire:ignore.self>
                                            <div class="max-w-xs mx-auto relative flex flex-col gap-2">
                                                <div class="flex items-center justify-between gap-2 my-1">
                                                    <input wire:model='minPrice' type="text" id="min_price"
                                                        data-input-counter aria-describedby="helper-text-explanation"
                                                        class="rounded-lg bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Min" required />
                                                    <input wire:model='maxPrice' type="text" id="max_price"
                                                        data-input-counter aria-describedby="helper-text-explanation"
                                                        class="rounded-lg bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Max" required />
                                                </div>
                                                <div class="flex items-center justify-center gap-2">
                                                    <button wire:click='setPrices' type="button" id="btnSortByPrice"
                                                        class="bg-red-500 p-2 rounded-lg text-white hover:bg-red-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click='resetPrices' type="button"
                                                        id="btnSortByPrice"
                                                        class="bg-red-500 p-2 rounded-lg text-white hover:bg-red-600">
                                                        <svg class="w-6 h-6" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="M17.651 7.65a7.131 7.131 0 0 0-12.68 3.15M18.001 4v4h-4m-7.652 8.35a7.13 7.13 0 0 0 12.68-3.15M6 20v-4h4" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @can('create products')
                                <button @click="$dispatch('open-product-create-modal')" type="button"
                                    class="bg-teal-500 flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-teal-600 focus:ring-4 focus:ring-primary-300 focus:outline-none">
                                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                    </svg>
                                    Add new product
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
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
                            @forelse ($this->products as $product)
                                <tr wire:key='product-tr-{{ $product->id }}' @class([
                                    'border-b border-gray-600',
                                    'hover:bg-gray-100' => !$product->deleted_at,
                                    'bg-red-100 hover:bg-red-200' => $product->deleted_at,
                                    'last:border-b-0' => false,
                                ])>

                                    <th scope="row"
                                        class="flex items-center px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="iMac Front Image"
                                            class="w-10 h-auto mr-3">
                                        {{ $product->name }}
                                    </th>
                                    <td class="px-4 py-2">
                                        <span
                                            class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300">{{ $product->category?->name ?? 'NULL' }}</span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span
                                            class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300">{{ $product->brand->name }}</span>
                                    </td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center">
                                            <x-stars :stars="$product->ratingAverage()" />
                                            <span
                                                class="ml-1 text-gray-500 dark:text-gray-400">{{ number_format($product->ratingAverage(), 2) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ App\Helpers::formatPrice($product->price) }} TL
                                    </td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24"
                                                fill="currentColor" class="w-5 h-5 mr-2 text-gray-400"
                                                aria-hidden="true">
                                                <path
                                                    d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
                                            </svg>
                                            {{ $product->totalSale() }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">{{ App\Helpers::formatPrice($product->revenue()) }} TL</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $product->updated_at }}</td>
                                    <td class="px-4 py-2">
                                        <button id="dropdownMenuIconButton-{{ $product->id }}"
                                            data-dropdown-toggle="dropdownDots-{{ $product->id }}"
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
                                        <div id="dropdownDots-{{ $product->id }}" style="z-index: 8888"
                                            class="hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 *:cursor-pointer"
                                                aria-labelledby="dropdownMenuIconButton-{{ $product->id }}">
                                                <li wire:click='showViewModal({{ $product->id }})'
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
                                                @can('edit products')
                                                    <li wire:click='showEditModal({{ $product->id }})'
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
                                                @if (!$product->deleted_at)
                                                    @can('delete products')
                                                        <li @click="$dispatch('delete-product-modal',{productId: {{ $product->id }}})"
                                                            class="px-4 py-2 hover:bg-red-500 group hover:text-white flex items-center gap-1">
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
                                                    @can('force delete products')
                                                        <li wire:click='restore({{ $product->id }})'
                                                            class="px-4 py-2 hover:bg-green-500 group hover:text-white flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="w-4 h-4 text-gray-800   group-hover:fill-white"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6a7 7 0 0 1 7-7a7 7 0 0 1 7 7a7 7 0 0 1-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.9 8.9 0 0 0 13 21a9 9 0 0 0 9-9a9 9 0 0 0-9-9" />
                                                            </svg>

                                                            <span>Restore</span>
                                                        </li>
                                                        <li @click="$dispatch('force-delete-product-modal',{productId: {{ $product->id }}})"
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
                                    <td colspan="9" class="text-2xl text-thin text-center">No products
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <nav class="p-2">
                    {{ $this->products->links() }}
                </nav>
            </div>
        </div>
    </section>

    {{-- modals --}}
    <x-admin.product.create-modal :categories="$this->createForm->categories()" :brands="$this->createForm->brands()"></x-admin.product.create-modal>
    <x-admin.product.view-modal :product="$selectedProduct"></x-admin.product.view-modal>
    <x-admin.product.edit-modal :product="$selectedProduct" :categories="$this->editForm->categories()" :brands="$this->editForm->brands()"></x-admin.product.edit-modal>
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
    </script>
@endscript
