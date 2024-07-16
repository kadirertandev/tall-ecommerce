<div class="bg-white text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full mb-4">
    <div class="flex items-center justify-between gap-12">
        <div class="flex-1 flex items-center justify-between bg-gray-50 ring-2 ring-gray-100 px-3 rounded-lg">
            <h1 class="text-3xl">{{ __('frontend.auth.dropdown-on-nav.orders') }}</h1>
        </div>
    </div>

    @forelse ($this->orders as $order)
        <div class="bg-gray-50 rounded-lg mb-4 shadow-md">
            <div
                class="bg-gray-200 text-gray-600 p-3 grid grid-cols-2 mb-4 rounded-t-lg border-b-2 pb-3 border-b-gray-200">
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
                        <div class="w-9/12 flex items-start justify-between gap-4">
                            <div class="flex gap-8 items-start ">
                                <a
                                    href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-16 h-auto"
                                        alt="">
                                </a>
                                <p class="flex items-center gap-2">
                                    <span>{{ $item->quantity }}</span>
                                    <svg class="mt-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                    </svg>
                                    <a class="hover:underline"
                                        href="{{ route('products.show', ['category_slug' => $item->product->category->slug, 'product_slug' => $item->product->slug]) }}">{{ $item->product->name }}</a>
                                </p>
                            </div>
                            <p class="">{{ App\Helpers::formatPrice($item->item_total_price) }} TL</p>
                        </div>
                        <div class="w-3/12 flex flex-col items-start {{-- justify-center --}}">
                            {{-- <div class="flex items-center justify-between">
                                <h1 class="font-semibold">Status</h1>
                                <h1>{{ $order->status }}</h1>
                            </div> --}}
                            <button wire:click='openCommentModalForProduct({{ $item->product->id }})'
                                class="border-2 border-zinc-400 hover:bg-gray-200 rounded-lg shadow-xl px-4 py-2">Ürün
                                Yorumu
                                Yazın</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="w-full sm:w-7/12 lg:w-9/12 flex items-center justify-between px-3 rounded-lg">
            <h1 class="text-xl my-2">No orders found.</h1>
        </div>
    @endforelse

    <x-user-profile-order-product-comment-modal :product="$productToComment" :rating="$rating">

    </x-user-profile-order-product-comment-modal>
</div>
