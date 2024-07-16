<div x-data="cartDrawer">
    <button @click="showDrawer"
        class="group hover:text-main-red  font-medium text-sm inline-flex items-center gap-1 rounded-md p-1"
        type="button">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 group-hover:fill-main-red">
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

        <h1>{{ __('frontend.cart.cart') }}</h1>
    </button>

    <!-- drawer component -->
    <div id="cart-drawer-right" wire:ignore.self
        class="fixed top-0 right-0 z-40 h-screen  overflow-y-auto transition-transform translate-x-full bg-white w-[405px] min-w-96 max-w-max dark:bg-gray-800"
        tabindex="-1" aria-labelledby="drawer-right-label">
        {{-- <h5 id="drawer-right-label"
            class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400"><svg
                class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>Right drawer</h5>
        <button type="button" @click="hideDrawer"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close menu</span>
        </button> --}}
        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
            @auth
                @if ($this->cartItemsCount > 0)
                    <div class="flex-1 {{-- overflow-y-auto --}} px-4 py-6 sm:px-6">
                        <div class="flex items-start justify-between">
                            <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">
                                {{ __('frontend.cart.shopping-cart') }}</h2>
                            <div class="ml-3 flex h-7 items-center">
                                <button @click="hideDrawer" type="button"
                                    class="relative -m-2 p-2 text-gray-400 hover:text-gray-500">
                                    <span class="absolute -inset-0.5"></span>
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="mt-8">
                            <div class="flow-root">
                                <ul role="list" class="-my-6 divide-y divide-gray-200">
                                    @foreach ($this->cartItems as $item)
                                        <li wire:key={{ $item->id }} class="flex py-6">
                                            <div
                                                class="h-24 w-24 flex-shrink-0 flex items-center overflow-hidden rounded-md border border-gray-200">
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                    alt="Salmon orange fabric pouch with match zipper, gray zipper pull, and adjustable hip belt."
                                                    class="w-full h-auto flex-1">
                                            </div>

                                            <div class="ml-4 flex flex-1 flex-col">
                                                <div>
                                                    <div class="{{-- flex justify-between --}} text-base font-medium text-gray-900">
                                                        <h3>
                                                            <a
                                                                href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}">{{ $item->product->name }}</a>
                                                        </h3>
                                                        <div class="{{-- ml-4 --}} text-nowrap">
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
                                                    {{-- <p class="mt-1 text-sm text-gray-500">Salmon</p> --}}
                                                </div>
                                                <div class="flex flex-1 items-end justify-between text-sm">
                                                    <p class="text-gray-500 flex items-center gap-4">
                                                        <span>{{ __('frontend.cart.quantity') }}</span>
                                                        <button wire:click='decreaseQuantity({{ $item->id }})'
                                                            {{-- @click="$dispatch('decrease-quantity', {id: {{ $item->id }}})" --}}
                                                            class="rounded-full p-4 hover:bg-gray-50 group">
                                                            <svg class="w-3 h-3 text-gray-800 group-hover:text-red-500 dark:text-white"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                width="24" height="24" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                                                            </svg>
                                                        </button>
                                                        <span>{{ $item->quantity }}</span>
                                                        <button wire:click='increaseQuantity({{ $item->id }})'
                                                            {{-- @click="$dispatch('increase-quantity', {id: {{ $item->id }}})" --}}
                                                            class="rounded-full p-4 hover:bg-gray-50 group">
                                                            <svg class="w-3 h-3 text-gray-800 group-hover:text-teal-500 dark:text-white"
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
                                                        <button type="button" {{-- wire:click='removeFromCart({{ $item->id }})' --}}
                                                            @click="$dispatch('remove-from-cart-modal', {cartItemId: {{ $item->id }}})"
                                                            class="font-medium text-indigo-600 hover:text-indigo-500">{{ __('frontend.cart.remove') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    {{-- <li class="flex py-6">
                                    <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                        <img src="https://tailwindui.com/img/ecommerce-images/shopping-cart-page-04-product-02.jpg"
                                            alt="Front of satchel with blue canvas body, black straps and handle, drawstring top, and front zipper pouch."
                                            class="h-full w-full object-cover object-center">
                                    </div>

                                    <div class="ml-4 flex flex-1 flex-col">
                                        <div>
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <h3>
                                                    <a href="#">Medium Stuff Satchel</a>
                                                </h3>
                                                <p class="ml-4">$32.00</p>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">Blue</p>
                                        </div>
                                        <div class="flex flex-1 items-end justify-between text-sm">
                                            <p class="text-gray-500">Qty 1</p>

                                            <div class="flex">
                                                <button type="button"
                                                    class="font-medium text-indigo-600 hover:text-indigo-500">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </li> --}}
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                        <div class="flex justify-between text-base font-medium text-gray-900">
                            <p>{{ __('frontend.cart.subtotal') }}</p>
                            <p>{{ App\Helpers::formatPrice($this->cart->subtotal()) }} TL</p>
                        </div>
                        {{-- <p class="mt-0.5 text-sm text-gray-500">Shipping and taxes calculated at checkout.</p> --}}
                        <div class="mt-6">
                            <a href="{{ route('auth.user.cart') }}"
                                class="flex items-center justify-center rounded-md border border-transparent bg-main-red px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700">{{ __('frontend.cart.checkout') }}</a>
                        </div>
                        {{-- <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                  <p>
                      or
                      <button type="button" class="font-medium text-indigo-600 hover:text-indigo-500">
                          Continue Shopping
                          <span aria-hidden="true"> &rarr;</span>
                      </button>
                  </p>
              </div> --}}
                    </div>
                @else
                    <div class=" {{-- overflow-y-auto --}} px-4 py-6 sm:px-6">
                        <div class="flex items-start justify-between">
                            <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">Shopping cart
                            </h2>
                            <div class="ml-3 flex h-7 items-center">
                                <button @click="hideDrawer" type="button"
                                    class="relative -m-2 p-2 text-gray-400 hover:text-gray-500">
                                    <span class="absolute -inset-0.5"></span>
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 {{-- bg-orange-200 --}} px-4 flex items-center justify-center">
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
