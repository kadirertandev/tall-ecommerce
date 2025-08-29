<div x-data="{ columns: 4 }"
    class="w-full mb-4 text-gray-500 bg-white rounded-lg text-medium dark:text-gray-400 dark:bg-gray-800">
    <div class="flex flex-col items-start gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div
            class="w-full sm:w-7/12 lg:w-9/12 flex items-center justify-between bg-gray-50 ring-2 ring-gray-100! px-3 rounded-lg">
            <h1 class="text-3xl">{{ __('frontend.favorites.favorites') }}</h1>
            @if ($this->favorites->total() > 0)
                <p>{{ $this->favorites->total() . ' ' . Str::lower(__('frontend.product.product')) }}
                </p>
            @endif
        </div>
        <div class="w-full sm:w-5/12 lg:w-3/12 flex place-content-end items-center gap-4 px-3 rounded-lg ">
            <button @click="columns = 2" class="items-center col-span-2 gap-4 rounded-lg flex place-content-end "
                type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
                    <path fill="black"
                        d="M22 12.999V20a1 1 0 0 1-1 1h-8v-8.001zm-11 0V21H3a1 1 0 0 1-1-1v-7.001zM11 3v7.999H2V4a1 1 0 0 1 1-1zm10 0a1 1 0 0 1 1 1v6.999h-9V3z" />
                </svg>
            </button>
            <button @click="columns = 4" class="items-center col-span-2 gap-4 rounded-lg flex place-content-end "
                type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 20 20">
                    <path fill="black"
                        d="M12 5.75a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 .75-.75m5 0a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 .75-.75m-10 0A.75.75 0 0 0 6.25 5h-2.5a.75.75 0 0 0 0 1.5h2.5A.75.75 0 0 0 7 5.75m5 3a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 .75-.75m5 0a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 .75-.75m-10 0A.75.75 0 0 0 6.25 8h-2.5a.75.75 0 0 0 0 1.5h2.5A.75.75 0 0 0 7 8.75m5 3a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 .75-.75m5 0a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 .75-.75m-10 0a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 .75-.75m5 3a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 .75-.75m5 0a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 .75-.75m-10 0a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 .75-.75" />
                </svg>
            </button>

            <h2 class="text-xl">{{ __('frontend.filters.sort') }}</h2>

            <div>
                <button id="dropdownDefaultButtonasdf" data-dropdown-toggle="dropdownasdf"
                    class="text-black bg-white ring-2 ring-gray-200! font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center "
                    type="button">
                    {{ $orderFrontend }}
                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <!-- Dropdown menu -->
                <div id="dropdownasdf" wire:ignore.self
                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                    <ul class="*:cursor-pointer py-2 text-sm text-gray-700 dark:text-gray-200"
                        aria-labelledby="dropdownDefaultButtonasdf">
                        <li wire:click="sortByOption('lowestPrice')">
                            <span
                                class="block px-4 py-2 hover:bg-gray-100 hover:text-red-500 dark:hover:bg-gray-600 ">{{ __('frontend.filters.lowest-price') }}</span>
                        </li>
                        <li wire:click="sortByOption('highestPrice')">
                            <span
                                class="block px-4 py-2 hover:bg-gray-100 hover:text-red-500 dark:hover:bg-gray-600 ">{{ __('frontend.filters.highest-price') }}</span>
                        </li>
                        <li wire:click="sortByOption('newest')">
                            <span
                                class="block px-4 py-2 hover:bg-gray-100 hover:text-red-500 dark:hover:bg-gray-600 ">{{ __('frontend.filters.newest') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- search --}}
    <div class="flex items-center justify-between mt-2">
        <div class="relative">
            <div class="absolute inset-y-0 flex items-center pointer-events-none start-0 ps-3">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms='search' type="search" id="default-search"
                class="block w-full p-4 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg ps-10 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white "
                placeholder="{{ Lang::get('frontend.filters.search-within-x-products', ['x' => $this->favorites->total()]) }}"
                required />
        </div>
    </div>
    {{-- search --}}

    {{-- category filter dropdown --}}
    <div class="flex items-center mt-2">
        <div class="relative">
            <button id="dropdownBgHoverButton" data-dropdown-toggle="dropdownBgHover"
                class="text-black bg-white ring-2 ring-gray-200! font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center "
                type="button">{{ __('frontend.categories') }}<svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 4 4 4-4" />
                </svg>
            </button>

            <!-- Dropdown menu -->
            <div id="dropdownBgHover" wire:ignore.self
                class="z-10 hidden w-48 bg-white rounded-lg shadow-lg dark:bg-gray-700" {{-- style="{{ $opened ? 'position: absolute; top: 50px; left: -10px' : '' }}" --}}>
                {{-- dropdown search --}}
                <div class="px-3 ">
                    <label for="input-group-search" class="sr-only">Search</label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 flex items-center pointer-events-none rtl:inset-r-0 start-0 ps-3">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms='searchCategory' type="text" id="input-group-search"
                            class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg ps-10 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search category">
                    </div>
                </div>
                {{-- dropdown search --}}
                <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200"
                    aria-labelledby="dropdownBgHoverButton">
                    @foreach ($this->categories as $id => $name)
                        <li wire:key='category-{{ md5($name) }}-{{ $id }}'>
                            <div class="flex items-center p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input wire:key='category-check-{{ $name }}-{{ $id }}'
                                    wire:model.live='categoriesFilter' id="category-check-{{ md5($name) }}"
                                    type="checkbox" value="{{ $id }}"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                <label for="category-check-{{ md5($name) }}"
                                    class="w-full text-sm font-medium text-gray-900 rounded-sm ms-2">{{ $name }}</label>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
    {{-- category filter dropdown --}}

    <div :class="columns == 4 ? 'mt-8 grid grid-cols-4 gap-3' : 'mt-8 grid grid-cols-2 gap-3'">
        @forelse ($this->favorites as $favorite)
            <div wire:key='favorite-card-{{ $favorite->id }}' class="p-3 shadow-lg ring-2 ring-gray-50!">
                <div class="relative flex items-center gap-2 md:flex-col md:gap-2">
                    <div class="w-full min-w-24 h-48 max-h-48 flex items-center justify-center">
                        <img src="{{ asset('/storage/' . $favorite->image) }}" class="w-auto max-h-48 aspect-auto"
                            alt="">
                    </div>
                    <div class="h-20 overflow-y-hidden">
                        <a
                            href="{{ route('products.show', ['category_slug' => $favorite->category->slug, 'product_slug' => $favorite->slug]) }}">{!! $favorite->title() !!}</a>
                    </div>
                    <livewire:add-to-favorites-button :key="$favorite->id" :productId="$favorite->id" type="profile"
                        :showLabel="false" />
                </div>
            </div>
        @empty
            <div class="flex items-center justify-between w-full px-3 rounded-lg sm:w-7/12 lg:w-9/12">
                <h1 class="my-2 text-xl">No products found.</h1>
            </div>
        @endforelse
    </div>

    <div class="my-4">
        {{ $this->favorites->links(data: ['scrollTo' => false]) }}
    </div>
</div>
