<div class="min-h-screen">
    <div class="py-4 main-container">
        <div>
            {!! \App\Helpers\Breadcrumbs::generate($this->breadcrumbs) !!}
        </div>
        <div class="w-full bg-white">
            <div>
                <main>
                    <section aria-labelledby="products-heading" class="pt-6 pb-4">
                        <div class="grid grid-cols-4 mb-4 gap-x-8">
                            <h1 class="text-3xl font-thin font-roboto">
                                {{ __('categories.' . $this->slug . '.name') }}
                            </h1>
                            <div class="flex items-center justify-between col-span-3">
                                @if ($this->products['total'] > 0)
                                    <p>{{ Lang::get('frontend.product.x-product-found', ['x' => $this->products['total']]) }}
                                @endif
                                </p>
                                <div>
                                    <button id="dropdownHoverButton2" data-dropdown-toggle="dropdownHover2"
                                        data-dropdown-trigger="hover"
                                        class="text-black ring-1! ring-gray-400! focus:outline-hidden  hover:ring-red-500! font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center"
                                        type="button"><span id="sort-text">{{ $orderFrontend }}</span> <svg
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="red" class="w-5 h-5 ms-2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                        </svg>
                                    </button>

                                    <!-- Dropdown menu -->
                                    <div id="dropdownHover2"
                                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                                        <ul class="*:cursor-pointer py-2 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby="dropdownHoverButton2">
                                            <li wire:click="sortByOption('lowestPrice')">
                                                <span
                                                    class="block px-4 py-2 hover:bg-gray-100 hover:text-red-500 dark:hover:bg-gray-600 ">{{ __('frontend.filters.lowest-price') }}</span>
                                            </li>
                                            <li wire:click="sortByOption('highestPrice')">
                                                <span
                                                    class="block px-4 py-2 hover:bg-gray-100 hover:text-red-500 dark:hover:bg-gray-600 ">{{ __('frontend.filters.highest-price') }}</span>
                                            </li>
                                            <li wire:click="sortByOption('mostLiked')">
                                                <span
                                                    class="block px-4 py-2 hover:bg-gray-100 hover:text-red-500 dark:hover:bg-gray-600 ">{{ __('frontend.filters.most-liked') }}</span>
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
                        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                            <!-- Filters -->
                            <form class="hidden lg:block " wire:ignore.self>
                                <div class="py-6 border-b border-gray-200" data-accordion="collapse" wire:ignore>
                                    <h3 class="flow-root -my-3" id="accordion-brand-collapse-heading">
                                        <button id="brand-accordion-button" type="button" data-name="brand"
                                            data-accordion-target="#accordion-brand-collapse-body"
                                            class="flex items-center justify-between w-full py-3 text-sm text-gray-400 bg-white accordion-button hover:text-gray-500"
                                            aria-expanded="true" aria-controls="accordion-brand-collapse-heading">
                                            <span class="font-medium text-gray-900">{{ __('frontend.brand') }}</span>
                                            <span class="flex items-center ml-6">
                                                <svg id="brand-accordion-svg-plus" class="hidden w-5 h-5"
                                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                </svg>
                                                <svg id="brand-accordion-svg-minus" class="w-5 h-5" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </h3>
                                    <div class="pt-6" id="accordion-brand-collapse-body"
                                        aria-labelledby="accordion-brand-collapse-heading">
                                        <div class="space-y-4">
                                            @foreach ($this->category->brands as $brand)
                                                <div wire:key='brand-{{ $brand->id }}-{{ $brand->name }}'
                                                    class="flex items-center">
                                                    <input wire:model.live='selectedBrands'
                                                        id="filter-brand-{{ $brand->id }}"
                                                        value="{{ $brand->id }}" type="checkbox"
                                                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded-sm focus:ring-indigo-500">
                                                    <label for="filter-brand-{{ $brand->id }}"
                                                        class="ml-3 text-sm text-gray-600">{{ $brand->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="py-6 border-b border-gray-200" data-accordion="collapse" wire:ignore>
                                    <h3 class="flow-root -my-3" id="accordion-price-collapse-heading">
                                        <button id="price-accordion-button" type="button" data-name="price"
                                            data-accordion-target="#accordion-price-collapse-body"
                                            class="flex items-center justify-between w-full py-3 text-sm text-gray-400 bg-white accordion-button hover:text-gray-500"
                                            aria-expanded="true" aria-controls="accordion-price-collapse-heading">
                                            <span class="font-medium text-gray-900">{{ __('frontend.price') }}</span>
                                            <span class="flex items-center ml-6">
                                                <svg id="price-accordion-svg-plus" class="hidden w-5 h-5"
                                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                </svg>
                                                <svg id="price-accordion-svg-minus" class="w-5 h-5" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </h3>
                                    <div class="pt-6" id="accordion-price-collapse-body"
                                        aria-labelledby="accordion-price-collapse-heading">
                                        <div class="space-y-4">
                                            <div x-data=""
                                                class="relative flex items-center max-w-xs gap-2 mx-auto">
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
                                                <button
                                                    @click="$wire.setPrices($refs.minPrice.value, $refs.maxPrice.value)"
                                                    type="button" id="btnSortByPrice"
                                                    class="p-2 text-white bg-red-500 rounded-lg hover:bg-red-600!">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                    </svg>
                                                </button>
                                                <button wire:click='resetPrices' type="button" id="btnSortByPrice"
                                                    class="p-2 text-white bg-red-500 rounded-lg hover:bg-red-600!">
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
                            </form>

                            <!-- Product grid -->
                            <div class="grid grid-cols-3 gap-2 lg:col-span-3 h-max">
                                @forelse ($this->products["products"] as $product)
                                    <div wire:key='product-container-{{ $product->id }}'
                                        class="flex flex-col justify-between gap-4 p-2 border-2 border-gray-100 shadow-xl">
                                        <div class="w-full flex justify-center flex-1 items-center">
                                            <a
                                                href="{{ route('products.show', ['category_slug' => $product->category->slug, 'product_slug' => $product->slug]) }}">
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    class="w-auto max-h-60" alt="">
                                            </a>
                                        </div>
                                        <div class="space-y-2">
                                            <h1>{!! $product->title() !!}</h1>
                                            <div class="flex gap-2">
                                                <div class="flex items-center">
                                                    <x-stars :stars="floor($product->review_rating_average)" />
                                                </div>
                                                @if ($product->review_count >= 1)
                                                    <h3 class="font-thin">({{ $product->review_rating_average }})</h3>
                                                @endif
                                            </div>
                                            <div class="flex flex-wrap items-end justify-between ">
                                                <div>
                                                    <h1 @class([
                                                        'text-3xl font-thin' => !$product->discount_amount,
                                                        'text-2xl text-[#9B9B9B] font-thin line-through decoration-2' =>
                                                            $product->discount_amount,
                                                    ])>
                                                        {{ App\Helpers::formatPrice($product->price) }} TL
                                                    </h1>
                                                    @if ($product->discount_amount)
                                                        <h1 @class(['text-3xl font-medium text-green-500'])>
                                                            {{ App\Helpers::formatPrice($product->finalPrice()) }}
                                                            TL
                                                        </h1>
                                                    @endif
                                                </div>
                                                <livewire:cart.add-to-cart-button :key='$product->id' :productId="$product->id"
                                                    :svg="false"
                                                    class="text-white bg-linear-to-r! from-teal-500! via-teal-600! to-teal-700! hover:bg-linear-to-br! focus:ring-2 focus:outline-hidden! focus:ring-teal-300! dark:focus:ring-teal-800! font-medium rounded-lg text-sm px-5 py-2.5 text-center" />
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="w-full col-span-3 p-2 text-white bg-main-red">
                                        <h1 class="flex flex-col items-center justify-center text-4xl">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16"
                                                viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M7 20.616q-1.671 0-2.835-1.165Q3 18.286 3 16.616q0-1.672 1.165-2.836Q5.329 12.616 7 12.616t2.836 1.164T11 16.616q0 1.67-1.164 2.835Q8.67 20.616 7 20.616m13.446-.462l-6.284-6.285q-.27.231-.645.443t-.701.327q-.108-.21-.234-.413t-.259-.368q1.258-.523 2.083-1.667T15.23 9.5q0-1.971-1.38-3.351t-3.35-1.38T7.149 6.15T5.769 9.5q0 .304.051.615q.051.31.115.594q-.22.012-.488.095t-.481.167q-.089-.313-.143-.694T4.769 9.5q0-2.398 1.667-4.064Q8.102 3.769 10.5 3.769t4.065 1.667T16.23 9.5q0 1.075-.376 2.028t-.957 1.657l6.256 6.261zM5.244 18.917L7 17.161l1.75 1.756l.552-.546l-1.756-1.755l1.756-1.756l-.546-.546L7 16.069l-1.756-1.755l-.546.545l1.756 1.756l-1.756 1.756z" />
                                            </svg>
                                            <span>No products found.</span>
                                        </h1>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
        <div class="grid grid-cols-4 mb-4 gap-x-8">
            <div></div>
            <div class="col-span-3">
                {{ $this->products['products']->links(data: ['scrollTo' => 'false']) }}
            </div>
        </div>
    </div>
</div>

@script
    <script>
        $(".accordion-button").on("click", function() {
            let name = $(this).data("name");

            $(`#${name}-accordion-svg-plus`).toggleClass("hidden");
            $(`#${name}-accordion-svg-minus`).toggleClass("hidden");
        })
    </script>
@endscript
