<x-layout>
    <div class="main-container flex gap-4 my-4">
        <div class="w-9/12">
            <h1 class="text-3xl font-thin font-roboto mb-2">{{ __('frontend.weekly_deals') }}</h1>
            <div id="controls-carousel" class="relative w-full" data-carousel="static">
                <!-- Carousel wrapper -->
                <div class="relative h-56 md:h-96 overflow-hidden rounded-lg">
                    <!-- Item 1 -->
                    @foreach ($weekly_deal_products as $product)
                        @if ($product->product && $product->product->category)
                            <div class="hidden duration-200 ease-linear" data-carousel-item>
                                <a href="{{ route('products.show', ['category_slug' => $product->product->category->slug, 'product_slug' => $product->product->slug]) }}"
                                    class="h-96 flex items-center justify-evenly bg-gray-300">
                                    <img class="w-72 max-h-80" src="{{ asset('storage/' . $product->product->image) }}"
                                        alt="">
                                    <div>
                                        <h1 class="text-3xl font-thin font-roboto text-zinc-800">
                                            {{ $product->product->name }}</h1>
                                        <h2
                                            class="text-2xl font-semibold font-roboto text-zinc-600 line-through decoration-2">
                                            {{ App\Helpers::formatPrice($product->product->price) }} TL
                                        </h2>
                                        <h2 class="text-3xl font-semibold font-roboto text-zinc-600">
                                            {{ App\Helpers::formatPrice($product->product->price - $product->discount_amount) }}
                                            TL
                                        </h2>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
                <!-- Slider controls -->
                <button type="button"
                    class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-prev>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 1 1 5l4 4" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button"
                    class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-next>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            </div>
        </div>
        <div class="w-3/12">
            <h1 class="text-3xl font-thin font-roboto mb-2">{{ __('frontend.daily_deals') }}</h1>
            <div id="controls-carousel" class="relative w-full" data-carousel="static">
                <!-- Carousel wrapper -->
                <div class="relative h-56 md:h-96 overflow-hidden rounded-lg ">
                    @foreach ($daily_deal_products as $product)
                        @if ($product->product && $product->product->category)
                            <div class="hidden duration-200 ease-linear" data-carousel-item>
                                <a href="{{ route('products.show', ['category_slug' => $product->product->category->slug, 'product_slug' => $product->product->slug]) }}"
                                    class="h-96 flex flex-col items-center justify-center bg-gray-300">
                                    <img class="max-w-80 h-48" src="{{ asset('storage/' . $product->product->image) }}"
                                        alt="">
                                    <div class="flex flex-col items-center">
                                        <h1 class="text-2xl font-thin font-roboto text-zinc-800">
                                            {{ $product->product->name }}</h1>
                                        <h2
                                            class="text-2xl font-semibold font-roboto text-zinc-600 line-through decoration-2 ">
                                            {{ App\Helpers::formatPrice($product->product->price) }} TL
                                        </h2>
                                        <h2 class="text-3xl font-semibold font-roboto text-zinc-600">
                                            {{ App\Helpers::formatPrice($product->product->price - $product->discount_amount) }}
                                            TL
                                        </h2>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
                <!-- Slider controls -->
                <button type="button"
                    class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-prev>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 1 1 5l4 4" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button"
                    class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-next>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div class="main-container pb-4">
        <h1 class="text-4xl font-thin font-roboto mt-2 mb-4">{{ __('frontend.popular-categories') }}</h1>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 *:text-white">
            @foreach ($popularCategories as $category)
                <a href="{{ route('category-slug', ['slug' => __('categories.' . $category->slug . '.slug')]) }}"
                    class="flex items-center gap-2 p-6 justify-center bg-neutral-300">
                    <img class="{{-- h-auto w-[100px] --}} w-[100px] max-w-[100px] h-auto rounded-lg"
                        src="{{ asset('/storage/' . $category->image) }}" alt="">
                    <h1 class=" text-2xl font-medium text-neutral-600">
                        {{ __('categories.' . $category->slug . '.name') }}
                    </h1>
                </a>
            @endforeach
        </div>
    </div>

    <div class="main-container pb-4">
        <h1 class="text-4xl font-thin font-roboto mt-2 mb-4">Popular Brands</h1>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 *:text-white">
            @foreach ($popularBrands as $brand)
                <a href="{{ route('brand-slug', ['slug' => $brand->slug]) }}"
                    class="flex items-center gap-2 p-2 justify-center bg-neutral-300">
                    <img class="{{-- h-auto w-[100px] --}} w-[100px] max-w-[200px] h-auto rounded-lg"
                        src="{{ asset('/storage/' . $brand->image) }}" alt="">
                    {{-- <h1 class=" text-2xl font-medium">
                        {{ $brand->name }}
                    </h1> --}}
                </a>
            @endforeach
        </div>
    </div>

    @section('script')
        <script>
            $(function() {
                // alert("Home jQuery")
            })
        </script>
    @endsection
    @section('script2')
        <script>
            $(function() {
                // alert("Home jQuery2")
            })
        </script>
    @endsection
</x-layout>
