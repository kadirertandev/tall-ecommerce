<div class="min-h-screen">
    <div class="py-4 main-container">
        <ul
            class="hidden text-sm font-medium text-center text-gray-500 rounded-lg shadow-sm sm:flex dark:divide-gray-700 dark:text-gray-400">
            <li class="w-full focus-within:z-10">
                <x-cart-step-button :order="1" :$step :disabled="$step == 1" />
            </li>
            <li class="w-full focus-within:z-10">
                <x-cart-step-button :order="2" :$step :disabled="$step != 3" />
            </li>
            <li class="w-full focus-within:z-10">
                <x-cart-step-button :order="3" :$step />
            </li>
        </ul>

        @if ($step == 1)
            <livewire:cart.cart-step-one />
        @endif
        @if ($step == 2)
            <livewire:cart.cart-step-two />
        @endif
        @if ($step == 3)
            <livewire:cart.cart-step-three />
        @endif


        @if (count($this->lastViewedProducts) > 0)
            <div class="mb-8">
                <h2 class="mb-2 text-2xl font-thin text-gray-900">Last Viewed Products</h2>
                <swiper-container wire:ignore class="mySwiper" navigation="true" space-between="30" slides-per-view="3"
                    loop="true" autoplay-delay="2500" autoplay-disable-on-interaction="false">
                    @foreach ($this->lastViewedProducts as $product)
                        <livewire:swiper.slide :key="'last-viewed-' . $product->id" :$product prefix="last-viewed" />
                    @endforeach
                </swiper-container>
            </div>
        @endif

        @if (Cache::has('weeklyDealProducts') || Cache::has('dailyDealProducts'))
            <h2 class="mb-2 text-2xl font-thin text-gray-900">Featured Products</h2>
            <swiper-container wire:ignore class="mySwiper" navigation="true" space-between="30" slides-per-view="3"
                loop="true" autoplay-delay="2500" autoplay-disable-on-interaction="false">
                @foreach ($weekly_deal_products ?? [] as $product)
                    <livewire:swiper.slide :key="'weekly-' . $product->product_id" :$product prefix="weekly" />
                @endforeach

                @foreach ($daily_deal_products ?? [] as $product)
                    <livewire:swiper.slide :key="'daily-' . $product->product_id" :$product prefix="daily" />
                @endforeach
            </swiper-container>
        @endif
    </div>
</div>
