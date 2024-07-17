<div class="w-full mb-4 text-gray-500 bg-white rounded-lg text-medium dark:text-gray-400 dark:bg-gray-800">
    <div class="flex flex-col items-start gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div
            class="{{-- col-span-4 --}}w-full sm:w-7/12 lg:w-9/12 flex items-center justify-between bg-gray-50 ring-2 ring-gray-100 px-3 rounded-lg">
            <h1 class="text-3xl">{{ __('frontend.favorites.favorites') }}</h1>
            @if ($this->favorites->count() > 0)
                <p>{{ $this->favoritesCount . ' ' . Str::lower(__('frontend.product.product')) }}</p>
            @endif
        </div>
        <div
            class="{{-- col-span-2 --}}w-full sm:w-5/12 lg:w-3/12 flex place-content-end items-center gap-4 px-3 rounded-lg ">
            <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                class="items-center hidden col-span-2 gap-4 px-3 rounded-lg md:flex place-content-end " type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
                    <path fill="black"
                        d="M22 12.999V20a1 1 0 0 1-1 1h-8v-8.001zm-11 0V21H3a1 1 0 0 1-1-1v-7.001zM11 3v7.999H2V4a1 1 0 0 1 1-1zm10 0a1 1 0 0 1 1 1v6.999h-9V3z" />
                </svg>
            </button>
            <!-- Dropdown menu -->
            <div id="dropdown" wire:ignore.self
                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                    <li wire:click='$set("cols",2)'>
                        <a href="#"
                            class="flex items-center px-4 py-2 group hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 group-hover:text-black"
                                viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1.5"
                                    d="M8.75 9.92c0-3.894 5.77-3.894 5.77 0c0 2.94-3.77 5.476-5.77 7.08c0 0 3.75-.625 6.25 0" />
                            </svg>
                            <span class="text-lg group-hover:text-black">Columns</span>
                        </a>
                    </li>
                    <li wire:click='$set("cols",4)'>
                        <a href="#"
                            class="flex items-center px-4 py-2 group hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 group-hover:text-black"
                                viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1.5"
                                    d="M12.917 7c-1.042 3.75-4.167 6.875-4.167 6.875H15M13.438 17v-5" />
                            </svg>
                            <span class="text-lg group-hover:text-black">Columns</span>
                        </a>
                    </li>
                </ul>
            </div>
            <h2 class="text-xl">{{ __('frontend.filters.sort') }}</h2>
            <div>
                <button id="dropdownDefaultButtonasdf" data-dropdown-toggle="dropdownasdf"
                    class="text-black bg-white ring-2 ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center "
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
                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                        aria-labelledby="dropdownDefaultButtonasdf">
                        <li wire:click='lastAdded'>
                            <div class="flex items-center px-4 py-2 cursor-pointer group hover:bg-gray-100 ">
                                {{ __('frontend.filters.newest') }}
                            </div>
                        </li>
                        <li wire:click='lowestPrice'>
                            <div class="flex items-center px-4 py-2 cursor-pointer group hover:bg-gray-100 ">
                                {{ __('frontend.filters.lowest-price') }}
                            </div>
                        </li>
                        <li wire:click='highestPrice'>
                            <div class="flex items-center px-4 py-2 cursor-pointer group hover:bg-gray-100 ">
                                {{ __('frontend.filters.highest-price') }}
                            </div>
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
                placeholder="{{ Lang::get('frontend.filters.search-within-x-products', ['x' => $this->favoritesCount]) }}"
                required />
        </div>
    </div>
    {{-- search --}}

    {{-- category filter dropdown --}}
    <div class="flex items-center mt-2">
        <div class="relative">
            <button id="dropdownBgHoverButton" data-dropdown-toggle="dropdownBgHover" {{-- wire:click='$toggle("opened")' --}}
                class="text-black bg-white ring-2 ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center "
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
                        <li wire:key='category-{{ md5($name) }}-{{ $id }}' {{-- wire:click='$set("opened",false)' --}}>
                            <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input wire:key='category-check-{{ $name }}-{{ $id }}'
                                    wire:model.live='categoriesFilter' id="category-check-{{ md5($name) }}"
                                    type="checkbox" value="{{ $id }}"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                <label for="category-check-{{ md5($name) }}"
                                    class="w-full text-sm font-medium text-gray-900 rounded ms-2">{{ $name }}</label>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
    {{-- category filter dropdown --}}

    <div wire:poll.10s
        class="mt-8 grid grid-cols-1 md:grid-cols-{{ $this->cols / 2 }} md:grid-cols-{{ $this->cols }} gap-3">
        @forelse ($this->favorites as $favorite)
            <div wire:key='favorite-card-{{ $favorite->name ? $favorite->id : $favorite->user_id }}'
                class="p-3 shadow-lg ring-4 ring-gray-50">
                <div class="relative flex items-center gap-2 md:flex-col md:gap-2">
                    <div class="w-full min-w-24 h-48 max-h-48 flex items-center justify-center {{-- bg-red-500 --}}">
                        <img src="{{ asset('/storage/' . ($favorite->name ? $favorite->image : $favorite->product->image)) }}"
                            class="w-auto max-h-48 aspect-auto" alt="">
                    </div>
                    <div class="h-20 overflow-y-hidden">
                        <a
                            href="{{ route('products.show', ['category_slug' => $favorite->name ? $favorite->category->slug : $favorite->product->category->slug, 'product_slug' => $favorite->name ? $favorite->slug : $favorite->product->slug]) }}">{!! $favorite->name ? $favorite->title() : $favorite->product->title() !!}</a>
                    </div>
                    <livewire:add-to-favorites-button :key="$favorite->name ? $favorite->id : $favorite->product->id" :product_slug="$favorite->name ? $favorite->slug : $favorite->product->slug" type="profile"
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
