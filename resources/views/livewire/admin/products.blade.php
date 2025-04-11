<div>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-(--breakpoint-2xl)">
            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div class="flex flex-row items-end justify-between px-4 lg:space-y-0 lg:space-x-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 ">
                            <h5>
                                <span class="text-gray-500">All Products:</span>
                                <span class="">{{ $this->products->total() }}</span>
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
                                @can('force delete products')
                                    <x-slot:toggles>
                                        <x-toggle toggle="withTrashed" text="With Trashed" />
                                        <x-toggle toggle="onlyTrashed" text="Only Trashed" />
                                    </x-slot:toggles>
                                @endcan
                                <x-slot:footer>
                                    <h2 id="accordion-collapse-heading-1">
                                        <button type="button"
                                            class="flex items-center justify-between w-full gap-3 p-2 font-medium text-gray-500 bg-white border-b-2 border-b-gray-300 rtl:text-right dark:text-gray-400"
                                            data-accordion-target="#accordion-collapse-body-1"
                                            aria-controls="accordion-collapse-body-1">
                                            <span>Category</span>
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
                                        <ul class="space-y-2 text-sm max-h-[200px] overflow-y-scroll"
                                            aria-labelledby="dropdownDefault">
                                            @foreach ($this->categories as $category)
                                                <li wire:key='filter-category-{{ $category->id }}'
                                                    class="flex items-center">
                                                    <input wire:model.live='categoriesFilter'
                                                        id="{{ $category->name . '-' . $category->id }}" type="checkbox"
                                                        value="{{ $category->id }}"
                                                        class="w-4 h-4 bg-gray-100 border-gray-300 rounded-sm text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

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
                                            class="flex items-center justify-between w-full gap-3 p-2 font-medium text-gray-500 bg-white border-b-2 border-b-gray-300 rtl:text-right dark:text-gray-400"
                                            data-accordion-target="#accordion-collapse-body-2"
                                            aria-controls="accordion-collapse-body-2">
                                            <span>Brand</span>
                                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 10 6">
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
                                                        id="{{ $brand->name . '-' . $brand->id }}" type="checkbox"
                                                        value="{{ $brand->id }}"
                                                        class="w-4 h-4 bg-gray-100 border-gray-300 rounded-sm text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

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
                                            class="flex items-center justify-between w-full gap-3 p-2 font-medium text-gray-500 bg-white border-b-2 border-b-gray-300 rtl:text-right dark:text-gray-400"
                                            data-accordion-target="#accordion-collapse-body-3"
                                            aria-controls="accordion-collapse-body-3">
                                            <span>Price</span>
                                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 10 6">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-3" class="hidden" x-data=""
                                        aria-labelledby="accordion-collapse-heading-3" wire:ignore.self>
                                        <div class="relative flex flex-col max-w-xs gap-2 mx-auto">
                                            <div class="flex items-center justify-between gap-2 my-1">
                                                <input wire:model='minPrice' x-ref="minPrice" type="number"
                                                    id="min_price" data-input-counter
                                                    aria-describedby="helper-text-explanation"
                                                    class="rounded-lg bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Min" required />
                                                <input wire:model='maxPrice' x-ref="maxPrice" type="number"
                                                    id="max_price" data-input-counter
                                                    aria-describedby="helper-text-explanation"
                                                    class="rounded-lg bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Max" required />
                                            </div>
                                            <div class="flex items-center justify-center gap-2">
                                                <button
                                                    @click="$wire.setPrices($refs.minPrice.value, $refs.maxPrice.value)"
                                                    type="button" id="btnSortByPrice"
                                                    class="p-2 text-white bg-red-500 rounded-lg hover:bg-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                    </svg>
                                                </button>
                                                <button wire:click='resetPrices' type="button" id="btnSortByPrice"
                                                    class="p-2 text-white bg-red-500 rounded-lg hover:bg-red-600">
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
                                </x-slot:footer>

                            </x-dropdown-filter>
                            @can('create products')
                                <button @click="$dispatch('open-modal', {name: 'create-product'})" type="button"
                                    class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 focus:ring-2 focus:ring-primary-300 focus:outline-hidden">
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
                                @foreach ($columns as $key => $value)
                                    <th wire:key='heading-{{ $key }}-{{ $value }}'
                                        wire:click='setSortBy("{{ $key }}")' scope="col"
                                        class="px-4 py-3">
                                        <x-sort-buttons :column="$key" :title="$value" :sortBy="$this->orderByColumn"
                                            :sortDir="$this->orderByDirection" />
                                    </th>
                                @endforeach
                                <th scope="col" class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->products as $product)
                                <tr wire:key='product-tr-{{ $product->id }}' @class([
                                    'border-b! border-gray-600!',
                                    'hover:bg-gray-100!' => !$product->deleted_at,
                                    'bg-red-100! hover:bg-red-200!' => $product->deleted_at,
                                    'last:border-b-0!' => false,
                                ])>

                                    <th scope="row"
                                        class="flex items-center px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="iMac Front Image"
                                            class="w-10 h-auto mr-3">
                                        {{ $product->name }}
                                    </th>
                                    <td class="px-4 py-2">
                                        <span
                                            class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded-sm dark:bg-primary-900 dark:text-primary-300">{{ $product->category?->name ?? 'NULL' }}</span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span
                                            class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded-sm dark:bg-primary-900 dark:text-primary-300">{{ $product->brand?->name ?? 'NULL' }}</span>
                                    </td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center">
                                            <x-stars :stars="floor($product->review_rating_average)" />
                                            <span
                                                class="ml-1 text-gray-500 dark:text-gray-400">{{ number_format($product->review_rating_average, 2) }}</span>
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
                                            {{ $product->total_sales ?? 0 }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">{{ App\Helpers::formatPrice($product->total_revenue) }} TL
                                    </td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $product->updated_at }}</td>
                                    <td class="px-4 py-2">
                                        <button id="dropdownMenuIconButton-{{ $product->id }}"
                                            data-dropdown-toggle="dropdownDots-{{ $product->id }}"
                                            data-dropdown-placement="left"
                                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-300 focus:ring-2 focus:outline-hidden dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
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
                                            class="hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 *:cursor-pointer"
                                                aria-labelledby="dropdownMenuIconButton-{{ $product->id }}">
                                                <li wire:click='showViewModal({{ $product->id }})'
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
                                                @can('edit products')
                                                    <li wire:click='showEditModal({{ $product->id }})'
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
                                                @if (!$product->deleted_at)
                                                    @can('delete products')
                                                        <li wire:click="askDeleteProduct({{ $product->id }})"
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
                                                    @can('force delete products')
                                                        <li wire:click='restore({{ $product->id }})'
                                                            class="flex items-center gap-1 px-4 py-2 hover:bg-green-500 group hover:text-white">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="w-4 h-4 text-gray-800 group-hover:fill-white"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6a7 7 0 0 1 7-7a7 7 0 0 1 7 7a7 7 0 0 1-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.9 8.9 0 0 0 13 21a9 9 0 0 0 9-9a9 9 0 0 0-9-9" />
                                                            </svg>

                                                            <span>Restore</span>
                                                        </li>
                                                        <li wire:click="askDeleteProduct({{ $product->id }}, {{ true }})"
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
                                    <td colspan="9" class="text-2xl text-center text-thin">No products
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

    <x-modals.admin.product.create-modal :brands="$this->createForm->brands()" />
    <x-modals.admin.product.view-modal :product="$selectedProduct" />
    <x-modals.admin.product.edit-modal :product="$selectedProduct" :brands="$this->editForm->brands()" />

</div>
