<div class="hidden md:block flex-1">
    <form action="">
        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative" @click.outside="$dispatch('hide-search-results')">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            {{-- {{ dump($thereAreResults) }} --}}
            <input wire:model.live.debounce.400ms="search" type="search" id="default-search"
                @keydown.escape.window="$wire.set('search','')"
                class="block w-full p-3 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
                            <h1 class="font-thin text-xl">Categories</h1>
                            @foreach ($this->categoryResults as $result)
                                {{-- <div
                                    class="group border-1 border-gray-200 rounded-md shadow-xl hover:bg-rose-500 cursor-pointer py-2 px-3">
                                    <a href="{{ route('category-slug', ['slug' => $result->slug]) }}"
                                        class="text-sm font-medium text-gray-600 group-hover:text-white group-hover:font-semibold">
                                        {{ $result->name }}</a>
                                </div> --}}

                                <a href="{{ route('category-slug', ['slug' => $result->slug]) }}"
                                    class="block border-1 text-sm font-medium text-gray-600 border-gray-200 rounded-md shadow-xl hover:bg-rose-500 hover:text-white hover:font-semibold cursor-pointer py-2 px-3">
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
                            <h1 class="font-thin text-xl">Brands</h1>
                            @foreach ($this->brandResults as $result)
                                {{-- <div
                                    class="group border-1 border-gray-200 rounded-md shadow-xl hover:bg-rose-500 cursor-pointer py-2 px-3">
                                    <a href="{{ route('brand-slug', ['slug' => $result->slug]) }}"
                                        class="text-sm font-medium text-gray-600 group-hover:text-white group-hover:font-semibold">
                                        {{ $result->name }}</a>
                                </div> --}}

                                <a href="{{ route('brand-slug', ['slug' => $result->slug]) }}"
                                    class="block border-1 text-sm font-medium text-gray-600 border-gray-200 rounded-md shadow-xl hover:bg-rose-500 hover:text-white hover:font-semibold cursor-pointer py-2 px-3">

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
                            <h1 class="font-thin text-xl">Products</h1>
                            @foreach ($this->productResults as $result)
                                <div
                                    class="group border-1 border-gray-200 rounded-md shadow-xl hover:bg-rose-500 cursor-pointer py-2 px-3">
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
