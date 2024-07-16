<x-header-meta :title="$product->name . ' ' . $product->description" />
<div class="min-h-screen bg-white">
    <x-header :nav1=true :nav2=true :nav3=true />
    <div class="main-container ">
        <div class="my-4">
            {!! \App\Helpers\Breadcrumbs::generate($breadcrumbs) !!}
        </div>
        <section
            class="py-8 mt-4 antialiased bg-white border-t-2 border-red-200 rounded-md shadow-2xl md:py-16 shadow-red-50 border-e-2">
            <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
                <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
                    <div class="max-w-md mx-auto shrink-0 lg:max-w-lg">
                        <img class="w-full " src="{{ asset('/storage/' . $product->image) }}" alt="" />
                        <img class="hidden w-full" src="{{ asset('/storage/' . $product->image) }}" alt="" />
                    </div>

                    <div class="mt-6 sm:mt-8 lg:mt-0">
                        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                            {!! $product->title() !!}
                        </h1>
                        <div class="mt-4 sm:items-start sm:gap-4 sm:flex">
                            <div>
                                <p @class([
                                    'text-3xl text-gray-900 font-extrabold' => !$product->discount_amount,
                                    'text-2xl text-[#9B9B9B] font-medium line-through decoration-2' =>
                                        $product->discount_amount,
                                ])>
                                    {{ App\Helpers::formatPrice($product->price) }} TL
                                </p>
                                @if ($product->discount_amount)
                                    <p @class(['text-3xl font-extrabold text-green-500'])>
                                        {{ App\Helpers::formatPrice($product->price - $product->discount_amount) }} TL
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                                <div class="flex items-center gap-1">
                                    <x-stars :stars="$product->ratingAverage()" />
                                </div>
                                <p class="text-sm font-medium leading-none text-gray-500 dark:text-gray-400">
                                    ({{ number_format($product->ratingAverage(), 1) }})
                                </p>
                                <a href="#reviews"
                                    class="text-sm font-medium leading-none text-gray-900 underline hover:no-underline dark:text-white">
                                    {{ $product->reviews()->count() }} {{ __('frontend.reviews') }}
                                </a>
                            </div>
                        </div>

                        <div class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8">
                            <livewire:add-to-cart-button :key='$product->id' :product_slug="$product_slug" :svg='true'
                                class="text-gray-900 mt-4 dark:hover:text-white sm:mt-0 bg-white hover:bg-teal-500 border-2 border-gray-200 font-medium rounded-lg shadow-xl text-sm px-5 py-2.5 dark:bg-primary-600 focus:outline-none flex items-center justify-center" />
                            <livewire:add-to-favorites-button wire:key='{{ $product->id }}' :product_slug="$product_slug"
                                type="show" />
                            {{-- <x-add-to-favorites-button :product="$product" :class="false" :svg="true" /> --}}
                        </div>

                        <hr class="my-6 border-gray-200 md:my-8 dark:border-gray-800" />

                        <p class="mb-6 text-gray-500 dark:text-gray-400">
                            Studio quality three mic array for crystal clear calls and voice
                            recordings. Six-speaker sound system for a remarkably robust and
                            high-quality audio experience. Up to 256GB of ultrafast SSD storage.
                        </p>

                        <p class="text-gray-500 dark:text-gray-400">
                            Two Thunderbolt USB 4 ports and up to two USB 3 ports. Ultrafast
                            Wi-Fi 6 and Bluetooth 5.0 wireless. Color matched Magic Mouse with
                            Magic Keyboard or Magic Keyboard with Touch ID.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <livewire:product-reviews :product_slug="$product_slug" />
    </div>
</div>
<x-footer />
<x-footer-meta>
    <x-slot:script>
        <script>
            function rate(stars) {
                $("#btnClearRating").removeClass("hidden");
                for (let i = 1; i <= stars; i++) {
                    $("#star-" + i).removeClass("text-gray-300")
                    $("#star-" + i).addClass("text-yellow-300")
                }

                for (let i = 5; i > stars; i--) {
                    $("#star-" + i).removeClass("text-yellow-300")
                    $("#star-" + i).addClass("text-gray-300")
                }
            }

            function clearRate() {
                rate(0);
                $("#btnClearRating").addClass("hidden");
            }
        </script>
    </x-slot>
</x-footer-meta>
