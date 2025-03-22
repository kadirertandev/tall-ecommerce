<div class="w-full mb-4 text-gray-500 bg-white rounded-lg text-medium dark:text-gray-400 dark:bg-gray-800">
    <div class="flex items-center justify-between gap-12">
        <div class="flex-1 flex items-center justify-between bg-gray-50 ring-2 ring-gray-100! px-3 rounded-lg">
            <h1 class="text-3xl">{{ __('frontend.auth.dropdown-on-nav.orders') }}</h1>
        </div>
    </div>

    @forelse ($this->orders as $order)
        <div class="mb-4 rounded-lg shadow-md bg-gray-50">
            <div
                class="grid grid-cols-2 p-3 pb-3 mb-4 text-gray-600 bg-gray-200 border-b-2 rounded-t-lg border-b-gray-200">
                <div>
                    <div class="grid grid-cols-2">
                        <h1 class="font-medium">Sipariş No</h1>
                        <h1 class="font-thin">{{ $order->id }}</h1>
                    </div>
                    <div class="grid grid-cols-2">
                        <h1 class="font-medium">Tarih</h1>
                        <h1 class="font-thin">{{ $order->created_at->toDayDateTimeString() }}</h1>
                    </div>
                    <div class="grid grid-cols-2">
                        <h1 class="font-medium">Sipariş Durumu</h1>
                        <h1 class="font-thin">{{ $order->status }}</h1>
                    </div>
                </div>
                <div>
                    <div class="grid grid-cols-2">
                        <h1 class="font-medium">Alıcı</h1>
                        <h1 class="font-thin">{{ $order->user->full_name() }}</h1>
                    </div>
                    <div class="grid grid-cols-2">
                        <h1 class="font-medium">Adres</h1>
                        <h1 class="font-thin">{{ $order->district }} / {{ $order->city }}</h1>
                    </div>
                    <div class="grid grid-cols-2">
                        <h1 class="font-medium">Toplam</h1>
                        <h1 class="font-thin">{{ App\Helpers::formatPrice($order->subtotal()) }} TL</h1>
                    </div>
                </div>
            </div>
            <div class="p-3">
                @foreach ($order->items as $item)
                    <div class="flex items-start justify-between gap-6 mb-2">
                        <div class="flex items-start justify-between w-9/12 gap-4">
                            <div class="flex items-start gap-8 ">
                                <a
                                    href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-16 h-auto"
                                        alt="">
                                </a>
                                <p class="flex items-center gap-2">
                                    <span>{{ $item->quantity }}</span>
                                    <svg class="w-5 h-5 mt-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                    </svg>
                                    <a class="hover:underline"
                                        href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}">{{ $item->product->name }}</a>
                                </p>
                            </div>
                            <p class="">{{ App\Helpers::formatPrice($item->subtotal()) }} TL</p>
                        </div>
                        <div class="w-3/12 flex flex-col items-start {{-- justify-center --}}">
                            {{-- <div class="flex items-center justify-between">
                                <h1 class="font-semibold">Status</h1>
                                <h1>{{ $order->status }}</h1>
                            </div> --}}
                            <button wire:click='openCommentModalForProduct({{ $item->product->id }})'
                                class="px-4 py-2 border-2 rounded-lg shadow-xl border-zinc-400 hover:bg-gray-200">Ürün
                                Yorumu
                                Yazın</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="flex items-center justify-between w-full px-3 rounded-lg sm:w-7/12 lg:w-9/12">
            <h1 class="my-2 text-xl">No orders found.</h1>
        </div>
    @endforelse

    <x-modals.user-profile-order-product-comment-modal :product="$productToComment" :rating="$rating" />
</div>
