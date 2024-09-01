<div class="flex-1 hidden md:block">
    <form class="max-w-lg mx-auto">
        <div class="flex">
            <label for="search-dropdown" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Your
                Email</label>
            <button id="dropdown-button" data-dropdown-toggle="dropdown-categories"
                class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600"
                type="button">
                <span>{{ $this->selectedCategoryTitle }}</span>
                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 4 4 4-4" />
                </svg>
            </button>
            <div id="dropdown-categories" wire:ignore
                class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                    <li>
                        <button @click="$wire.setSelectedCategory('All Categories',0)" type="button"
                            class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">All
                            Categories</button>
                    </li>
                    @foreach ($categories as $category)
                        <li>
                            <button type="button"
                                @click="$wire.setSelectedCategory('{{ __('categories.' . $category->slug . '.name') }}',{{ $category->id }})"
                                class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                {{ !Str::startsWith(__('categories.' . $category->slug . '.name'), 'categories.') ? __('categories.' . $category->slug . '.name') : __('categories.' . __('categories.dictionary.' . $category->slug) . '.name') }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="relative w-full" @click.outside="$dispatch('hide-search-results')">
                <input wire:model.live.debounce.400ms="search" type="search" id="search-dropdown"
                    @keydown.escape.window="$wire.set('search','')"
                    class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-s-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                    placeholder="Search products, categories or brands" required />
                @if ($thereAreResults)

                    <div class="absolute w-full bg-white shadow-2xl border-2 border-gray-50 z-[9999]">
                        <div class="grid grid-cols-10 gap-2 p-2">
                            <div @class([
                                'col-span-2' =>
                                    count($this->brandResults) > 0 && count($this->productResults) > 0,
                                'col-span-3' =>
                                    count($this->brandResults) <= 0 && count($this->productResults) > 0,
                                'col-span-5' =>
                                    count($this->brandResults) > 0 && count($this->productResults) <= 0,
                                'hidden' => count($this->categoryResults) <= 0,
                                'block' => $this->categoryResults,
                            ])>
                                <h1 class="text-xl font-thin">Categories</h1>
                                @foreach ($this->categoryResults as $result)
                                    {{-- <div
                                    class="px-3 py-2 border-gray-200 rounded-md shadow-xl cursor-pointer group border-1 hover:bg-rose-500">
                                    <a href="{{ route('category-slug', ['slug' => $result->slug]) }}"
                                        class="text-sm font-medium text-gray-600 group-hover:text-white group-hover:font-semibold">
                                        {{ $result->name }}</a>
                                </div> --}}

                                    <a href="{{ route('category-slug', ['slug' => $result->slug]) }}"
                                        class="block px-3 py-2 text-sm font-medium text-gray-600 border-gray-200 rounded-md shadow-xl cursor-pointer border-1 hover:bg-rose-500 hover:text-white hover:font-semibold">
                                        {{ $result->name }} </a>
                                @endforeach
                            </div>
                            <div @class([
                                'col-span-2' =>
                                    count($this->categoryResults) > 0 && count($this->productResults) > 0,
                                'col-span-3' =>
                                    count($this->categoryResults) <= 0 && count($this->productResults) > 0,
                                'col-span-5' =>
                                    count($this->categoryResults) > 0 && count($this->productResults) <= 0,
                                'hidden' => count($this->brandResults) <= 0,
                                'block' => count($this->brandResults) > 0,
                            ])>
                                <h1 class="text-xl font-thin">Brands</h1>
                                @foreach ($this->brandResults as $result)
                                    {{-- <div
                                    class="px-3 py-2 border-gray-200 rounded-md shadow-xl cursor-pointer group border-1 hover:bg-rose-500">
                                    <a href="{{ route('brand-slug', ['slug' => $result->slug]) }}"
                                        class="text-sm font-medium text-gray-600 group-hover:text-white group-hover:font-semibold">
                                        {{ $result->name }}</a>
                                </div> --}}

                                    <a href="{{ route('brand-slug', ['slug' => $result->slug]) }}"
                                        class="block px-3 py-2 text-sm font-medium text-gray-600 border-gray-200 rounded-md shadow-xl cursor-pointer border-1 hover:bg-rose-500 hover:text-white hover:font-semibold">

                                        {{ $result->name }}</a>
                                @endforeach
                            </div>
                            <div @class([
                                'col-span-6' =>
                                    count($this->categoryResults) > 0 && count($this->brandResults) > 0,
                                'col-span-7' =>
                                    count($this->categoryResults) <= 0 || count($this->brandResults) <= 0,
                                'col-span-10' =>
                                    count($this->categoryResults) <= 0 && count($this->brandResults) <= 0,
                                'hidden' => count($this->productResults) <= 0,
                                'block' => $this->productResults,
                            ])>
                                <h1 class="text-xl font-thin">Products</h1>
                                @foreach ($this->productResults as $result)
                                    <div
                                        class="px-3 py-2 border-gray-200 rounded-md shadow-xl cursor-pointer group border-1 hover:bg-rose-500">
                                        <a href="{{ route('products.show', ['category_slug' => $result->category->slug, 'product_slug' => $result->slug]) }}"
                                            class="flex items-center gap-2 text-sm font-medium text-gray-600 group-hover:text-white group-hover:font-semibold">
                                            <img src="{{ asset('storage/' . $result->image) }}" class="w-16 h-auto"
                                                alt="">
                                            <span>{{ $result->name }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @if (!$thereAreResults && strlen($this->search) > 2)
                    <div class="absolute w-full bg-white shadow-2xl border-2 border-gray-50 z-[9999]">
                        <div class="p-2">
                            No results.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>

</div>

@script
    <script>
        window.addEventListener("hide-search-results", function() {
            $wire.set("thereAreResults", false);
            $wire.set("search", "");
        })
    </script>
@endscript
