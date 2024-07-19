<div class="min-h-screen">
    <div class="py-4 main-container">
        <ul
            class="hidden text-sm font-medium text-center text-gray-500 rounded-lg shadow sm:flex dark:divide-gray-700 dark:text-gray-400">
            <li class="w-full focus-within:z-10">
                <button @click="$wire.set('step',1)" @disabled($step == 1) @class([
                    'inline-flex justify-between w-full p-2 text-gray-900 border-r border-gray-200  rounded-s-lg focus:ring-4 focus:ring-blue-300 active focus:outline-none dark:bg-gray-700 dark:text-white',
                    'bg-gray-300' => $step == 1,
                    'bg-gray-100' => $step != 1,
                ])
                    aria-current="page">
                    <div class="flex items-center gap-4 text-3xl">
                        <svg xmlns="http://www.w3.org/2000/svg" @class([
                            'w-10 h-10',
                            'text-[#cbcaca]' => $step != 1,
                            'text-white' => $step == 1,
                        ]) viewBox="0 0 16 16">
                            <path fill="currentColor"
                                d="M14 13.1V12H4.6l.6-1.1l9.2-.9L16 4H3.7L3 1H0v1h2.2l2.1 8.4L3 13v1.5c0 .8.7 1.5 1.5 1.5S6 15.3 6 14.5S5.3 13 4.5 13H12v1.5c0 .8.7 1.5 1.5 1.5s1.5-.7 1.5-1.5c0-.7-.4-1.2-1-1.4" />
                        </svg>
                        <span class="font-thin">{{ __('frontend.cart.cart') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($step >= 2)
                            <svg xmlns="http://www.w3.org/2000/svg" @class([
                                'w-12 h-12',
                                'text-[#cbcaca]' => $step != 1,
                                'text-white' => $step == 1,
                            ]) viewBox="0 0 15 15">
                                <path fill="currentColor" fill-rule="evenodd"
                                    d="M14.707 3L5.5 12.207L.293 7L1 6.293l4.5 4.5l8.5-8.5z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <h1 @class([
                            'font-roboto font-semibold text-6xl',
                            'text-[#cbcaca]' => $step != 1,
                            'text-white' => $step == 1,
                        ])>1</h1>
                    </div>
                </button>
            </li>
            <li class="w-full focus-within:z-10">
                {{-- <button @click="$wire.set('step',2)" @disabled($step == 1 || $step == 2)
                  class="inline-block w-full p-4 bg-white border-r border-gray-200 dark:border-gray-700 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">Adres
                  Seçimi</button> --}}
                <button @click="$wire.set('step',2)" @disabled($step == 1 || $step == 2) @class([
                    'inline-flex justify-between w-full p-2 text-gray-900 border-r border-gray-200  focus:ring-4 focus:ring-blue-300 active focus:outline-none dark:bg-gray-700 dark:text-white',
                    'bg-gray-300' => $step == 2,
                    'bg-gray-100' => $step != 2,
                ])
                    aria-current="page">
                    <div class="flex items-center gap-4 text-3xl">
                        <svg xmlns="http://www.w3.org/2000/svg" @class([
                            'w-12 h-12',
                            'text-[#cbcaca]' => $step != 2,
                            'text-white' => $step == 2,
                        ]) viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12 6.5A2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5A2.5 2.5 0 0 1 9.5 9A2.5 2.5 0 0 1 12 6.5M12 2a7 7 0 0 1 7 7c0 5.25-7 13-7 13S5 14.25 5 9a7 7 0 0 1 7-7m0 2a5 5 0 0 0-5 5c0 1 0 3 5 9.71C17 12 17 10 17 9a5 5 0 0 0-5-5" />
                        </svg>
                        <span class="font-thin text-nowrap">{{ __('frontend.cart.address-selection') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($step == 3)
                            <svg xmlns="http://www.w3.org/2000/svg" @class([
                                'w-12 h-12',
                                'text-[#cbcaca]' => $step != 2,
                                'text-white' => $step == 2,
                            ]) viewBox="0 0 15 15">
                                <path fill="currentColor" fill-rule="evenodd"
                                    d="M14.707 3L5.5 12.207L.293 7L1 6.293l4.5 4.5l8.5-8.5z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <h1 @class([
                            'font-roboto font-semibold text-6xl',
                            'text-[#cbcaca]' => $step != 2,
                            'text-white' => $step == 2,
                        ])>2</h1>
                    </div>
                </button>
            </li>
            <li class="w-full focus-within:z-10">
                {{-- <button disabled
                  class="inline-block w-full p-4 bg-white border-r border-gray-200 dark:border-gray-700 rounded-e-lg hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">Sipariş
                  Onayı</button> --}}
                <button @click="$wire.set('step',1)" disabled @class([
                    'inline-flex justify-between w-full p-2 text-gray-900 border-r border-gray-200  rounded-e-lg focus:ring-4 focus:ring-blue-300 active focus:outline-none dark:bg-gray-700 dark:text-white',
                    'bg-gray-300' => $step == 3,
                    'bg-gray-100' => $step != 3,
                ]) aria-current="page">
                    <div class="flex items-center gap-4 text-3xl">
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" @class([
                          'w-10 h-10',
                          'text-[#cbcaca]' => $step != 3,
                          'text-white' => $step == 3,
                      ]) viewBox="0 0 16 16">
                          <path fill="currentColor"
                              d="M14 13.1V12H4.6l.6-1.1l9.2-.9L16 4H3.7L3 1H0v1h2.2l2.1 8.4L3 13v1.5c0 .8.7 1.5 1.5 1.5S6 15.3 6 14.5S5.3 13 4.5 13H12v1.5c0 .8.7 1.5 1.5 1.5s1.5-.7 1.5-1.5c0-.7-.4-1.2-1-1.4" />
                      </svg> --}}
                        <svg xmlns="http://www.w3.org/2000/svg" @class([
                            'w-12 h-12',
                            'text-[#cbcaca]' => $step != 3,
                            'text-white' => $step == 3,
                        ]) viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M3 19V5h18v14zM4 8.5h16V6H4zm11.775 5.594L20 10.525V9.5H4v1.725z" />
                        </svg>
                        <span class="font-thin">{{ __('frontend.cart.order-confirmation') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($step == 4)
                            <svg xmlns="http://www.w3.org/2000/svg" @class([
                                'w-12 h-12',
                                'text-[#cbcaca]' => $step != 3,
                                'text-white' => $step == 3,
                            ]) viewBox="0 0 15 15">
                                <path fill="currentColor" fill-rule="evenodd"
                                    d="M14.707 3L5.5 12.207L.293 7L1 6.293l4.5 4.5l8.5-8.5z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <h1 @class([
                            'font-roboto font-semibold text-6xl',
                            'text-[#cbcaca]' => $step != 3,
                            'text-white' => $step == 3,
                        ])>3</h1>
                    </div>
                </button>
            </li>
        </ul>

        @if ($step == 1)
            <livewire:cart-step-one />
        @endif
        @if ($step == 2)
            <livewire:cart-step-two />
        @endif
        @if ($step == 3)
            <livewire:cart-step-three />
        @endif


        @if (count($this->lastViewedProducts) > 0)
            <div class="mb-8">
                <h2 class="mb-2 text-2xl font-thin text-gray-900">Last Viewed Products</h2>
                <swiper-container wire:ignore class="mySwiper" navigation="true" {{-- pagination-clickable="true" --}}
                    space-between="30" slides-per-view="3" loop="true" autoplay-delay="2500"
                    autoplay-disable-on-interaction="false">
                    @foreach ($this->lastViewedProducts as $deal)
                        <livewire:swiper.slide :key="'last-viewed-' . $deal->id" :$deal prefix="last-viewed" />
                    @endforeach
                </swiper-container>
            </div>
        @endif

        @if (Cache::has('weeklyDealProducts') || Cache::has('dailyDealProducts'))
            <h2 class="mb-2 text-2xl font-thin text-gray-900">Featured Products</h2>
            <swiper-container wire:ignore class="mySwiper" navigation="true" {{-- pagination-clickable="true" --}} space-between="30"
                slides-per-view="3" loop="true" autoplay-delay="2500" autoplay-disable-on-interaction="false">
                @if (Cache::has('weeklyDealProducts'))
                    @foreach ($weekly_deal_products as $deal)
                        <livewire:swiper.slide :key="'weekly-' . $deal->product_id" :$deal prefix="weekly" />
                    @endforeach
                @endif

                @if (Cache::has('dailyDealProducts'))
                    @foreach ($daily_deal_products as $deal)
                        <livewire:swiper.slide :key="'daily-' . $deal->product_id" :$deal prefix="daily" />
                    @endforeach
                @endif
            </swiper-container>
        @endif
    </div>
</div>
