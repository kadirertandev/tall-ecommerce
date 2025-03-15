<div x-data="cartDrawer">
    <button @click="showDrawer"
        class="inline-flex items-center gap-1 p-1 text-sm font-medium rounded-md group hover:text-main-red"
        type="button">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 group-hover:fill-main-red group-hover:stroke-main-red">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
            @auth
                @if ($this->cartItemsCount > 0)
                    <div
                        class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-3 -end-3 dark:border-gray-900">
                        {{ $this->cartItemsCount }}</div>
                @endif
            @endauth
        </div>

        <h1 class="group-hover:text-main-red">{{ __('frontend.cart.cart') }}</h1>
    </button>

    <!-- drawer component -->
    <div id="cart-drawer-right" wire:ignore.self
        class="fixed top-0 right-0 z-40 h-screen  overflow-y-auto transition-transform translate-x-full bg-white w-[405px] min-w-96 max-w-max dark:bg-gray-800"
        tabindex="-1" aria-labelledby="drawer-right-label">
        <div class="flex flex-col h-full overflow-y-scroll bg-white shadow-xl">
            @auth
                @if ($this->cartItemsCount > 0)
                    <div class="flex-1 px-4 py-6 sm:px-6">
                        <div class="flex items-start justify-between">
                            <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">
                                {{ __('frontend.cart.shopping-cart') }}</h2>
                            <div class="flex items-center ml-3 h-7">
                                <button @click="hideDrawer" type="button"
                                    class="relative text-gray-400 hover:text-gray-500">
                                    <span class="absolute -inset-0.5"></span>
                                    <span class="sr-only">Close panel</span>
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="">
                            <div class="flow-root">
                                <ul role="list" class="-my-6 divide-y divide-gray-200">
                                    @foreach ($this->cartItems as $item)
                                        <li wire:key={{ $item->id }} class="flex py-6">
                                            <div
                                                class="flex items-center w-24 h-24 overflow-hidden border border-gray-200 rounded-md shrink-0">
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                    alt="Salmon orange fabric pouch with match zipper, gray zipper pull, and adjustable hip belt."
                                                    class="flex-1 w-full h-auto">
                                            </div>

                                            <div class="flex flex-col flex-1 ml-4">
                                                <div>
                                                    <div class="text-base font-medium text-gray-900">
                                                        <h3>
                                                            <a
                                                                href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}">{{ $item->product->name }}</a>
                                                        </h3>
                                                        <div class="text-nowrap">
                                                            @if ($item->product->discount_amount)
                                                                <p
                                                                    class="text-[#9B9B9B] font-thin line-through decoration-2">
                                                                    {{ App\Helpers::formatPrice($item->price * $item->quantity) }}
                                                                    TL</p>
                                                            @endif
                                                            <p class="text-xl">
                                                                {{ App\Helpers::formatPrice($item->item_total_price) }} TL
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between flex-1 text-sm">
                                                    <p class="flex items-center gap-4 text-gray-500">
                                                        <span>{{ __('frontend.cart.quantity') }}</span>
                                                        <button wire:click='decreaseQuantity({{ $item->id }})'
                                                            class="p-4 rounded-full hover:bg-gray-50 group">
                                                            <svg class="w-3 h-3 text-gray-800 group-hover:text-red-500! dark:text-white"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                width="24" height="24" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                                                            </svg>
                                                        </button>
                                                        <span>{{ $item->quantity }}</span>
                                                        <button wire:click='increaseQuantity({{ $item->id }})'
                                                            class="p-4 rounded-full hover:bg-gray-50 group">
                                                            <svg class="w-3 h-3 text-gray-800 group-hover:text-teal-500! dark:text-white"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                width="24" height="24" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M5 12h14m-7 7V5" />
                                                            </svg>
                                                        </button>
                                                    </p>
                                                    <div class="flex">
                                                        <button type="button"
                                                            wire:click='askRemoveFromCart({{ $item->id }})'
                                                            class="font-medium text-red-400! hover:bg-gray-100 hover:text-red-600! p-2 rounded-lg">{{ __('frontend.cart.remove') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-6 border-t border-gray-200 sm:px-6">
                        <div class="flex justify-between text-base font-medium text-gray-900">
                            <p>{{ __('frontend.cart.subtotal') }}</p>
                            <p>{{ App\Helpers::formatPrice($this->cart->subtotal()) }} TL</p>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('auth.user.cart') }}"
                                class="flex items-center justify-center px-6 py-3 text-base font-medium text-white border border-transparent rounded-md shadow-xs bg-main-red hover:bg-indigo-700!">{{ __('frontend.cart.checkout') }}</a>
                        </div>
                    </div>
                @else
                    <div class="px-4 py-6 sm:px-6">
                        <div class="flex items-start justify-between">
                            <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">Shopping cart
                            </h2>
                            <div class="flex items-center ml-3 h-7">
                                <button @click="hideDrawer" type="button"
                                    class="relative p-2 -m-2 text-gray-400 hover:text-gray-500">
                                    <span class="absolute -inset-0.5"></span>
                                    <span class="sr-only">Close panel</span>
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center flex-1 px-4">
                        <div class="flex flex-col items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-red-500" viewBox="0 0 16 16">
                                <g fill="currentColor">
                                    <path
                                        d="M7.354 5.646a.5.5 0 1 0-.708.708L7.793 7.5L6.646 8.646a.5.5 0 1 0 .708.708L8.5 8.207l1.146 1.147a.5.5 0 0 0 .708-.708L9.207 7.5l1.147-1.146a.5.5 0 0 0-.708-.708L8.5 6.793z" />
                                    <path
                                        d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607l1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4a2 2 0 0 0 0-4h7a2 2 0 1 0 0 4a2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0a1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0a1 1 0 0 1 2 0" />
                                </g>
                            </svg>
                            <h1 class="text-3xl font-thin">{{ __('frontend.cart.cart-empty') }}</h1>
                        </div>
                    </div>
                @endif
            @endauth
            @guest
                <div>
                    <livewire:auth.login-form />
                </div>
            @endguest
        </div>
    </div>
</div>
@assets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
@endassets
@script
    <script>
        let $targetEl = document.getElementById('cart-drawer-right');

        // options with default values
        let options = {
            placement: 'right',
            backdrop: true,
            bodyScrolling: false,
            edge: false,
            edgeOffset: '',
            backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-30',
            onHide: () => {
                console.log('drawer is hidden');
            },
            onShow: () => {
                console.log('drawer is shown');
            },
            onToggle: () => {
                console.log('drawer has been toggled');
            },
        };

        // instance options object
        let instanceOptions = {
            id: 'drawer-js-example',
            override: true
        };
        let drawer = new Drawer($targetEl, options, instanceOptions);
        console.log(drawer)
        Alpine.data("cartDrawer", () => ({
            drawer,
            deneme() {
                alert("deneme")
            },
            showDrawer() {
                if (drawer.isHidden()) {
                    drawer.show()
                }
            },
            hideDrawer() {
                if (drawer.isVisible()) {
                    drawer.hide()
                }
            }
        }))
    </script>
@endscript
