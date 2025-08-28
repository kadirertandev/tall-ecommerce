<div>
    <div class="main-container">
        <div class="my-4">
            {!! \App\Helpers\Breadcrumbs::generate($breadcrumbs) !!}
        </div>
        <section
            class="py-8 mt-4 antialiased bg-white border-t-2! border-red-200! rounded-md shadow-2xl! md:py-16 shadow-red-50! border-e-2!">

            <div class="max-w-(--breakpoint-xl) px-4 mx-auto 2xl:px-0">
                <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
                    <div class="max-w-md mx-auto shrink-0 lg:max-w-lg">
                        <img class="w-full " src="{{ asset('/storage/' . $this->product->image) }}" alt="" />
                        <img class="hidden w-full" src="{{ asset('/storage/' . $this->product->image) }}"
                            alt="" />
                    </div>

                    <div class="mt-6 sm:mt-8 lg:mt-0">
                        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                            {!! $this->product->title() !!}
                        </h1>
                        <div class="mt-4 sm:items-start sm:gap-4 sm:flex">
                            <div>
                                <p @class([
                                    'text-3xl text-gray-900 font-extrabold' => !$this->product->discount_amount,
                                    'text-2xl text-[#9B9B9B] font-medium line-through decoration-2' =>
                                        $this->product->discount_amount,
                                ])>
                                    {{ App\Helpers::formatPrice($this->product->price) }} TL
                                </p>
                                @if ($this->product->discount_amount)
                                    <p @class(['text-3xl font-extrabold text-green-500'])>
                                        {{ App\Helpers::formatPrice($this->product->finalPrice()) }}
                                        TL
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                                <div class="flex items-center gap-1">
                                    <x-stars :stars="floor($this->product->review_rating_average)" />
                                </div>
                                <p class="text-sm font-medium leading-none text-gray-500 dark:text-gray-400">
                                    ({{ number_format($this->product->review_rating_average, 1) }})
                                </p>
                                <a href="#reviews"
                                    class="text-sm font-medium leading-none text-gray-900 underline hover:no-underline dark:text-white">
                                    {{ $this->product->review_count }} {{ __('frontend.reviews') }}
                                </a>
                            </div>
                        </div>

                        <div class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8">
                            <livewire:cart.add-to-cart-button :productId="$this->product->id" :svg='true'
                                class="text-gray-900 mt-4 hover:text-white sm:mt-0 bg-white hover:bg-teal-500! border-2 border-gray-200 font-medium rounded-lg shadow-xl text-sm px-5 py-2.5 dark:bg-primary-600 focus:outline-hidden flex items-center justify-center" />
                            <livewire:add-to-favorites-button :productId="$this->product->id" type="show" />
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

        <livewire:product-reviews :productId="$this->product->id" :reviewCount="$this->product->review_count" />
    </div>
</div>
