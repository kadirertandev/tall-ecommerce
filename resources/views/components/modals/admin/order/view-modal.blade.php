@props(['order'])
<x-modal name="view-order" :title="$order?->user->full_name() . '\'s Order'">
    <div class="p-4 space-y-2">
        <div class="grid grid-cols-2 p-3 pb-3 mb-4 text-gray-600 bg-gray-200 border-b-2 rounded-t-lg border-b-gray-200">
            <div>
                <div class="grid grid-cols-2">
                    <h1 class="font-medium">Sipariş No</h1>
                    <h1 class="font-thin">{{ $order?->id }}</h1>
                </div>
                <div class="grid grid-cols-2">
                    <h1 class="font-medium">Tarih</h1>
                    <h1 class="font-thin">{{ $order?->created_at->toDayDateTimeString() }}</h1>
                </div>
                <div class="grid grid-cols-2">
                    <h1 class="font-medium">Sipariş Durumu</h1>
                    <h1 class="font-thin">{{ $order?->status }}</h1>
                </div>
            </div>
            <div>
                <div class="grid grid-cols-2">
                    <h1 class="font-medium">Alıcı</h1>
                    <h1 class="font-thin">{{ $order?->user->full_name() }}</h1>
                </div>
                <div class="grid grid-cols-2">
                    <h1 class="font-medium">Adres</h1>
                    <h1 class="font-thin">{{ $order?->district }} / {{ $order?->city }}</h1>
                </div>
                <div class="grid grid-cols-2">
                    <h1 class="font-medium">Toplam</h1>
                    <h1 class="font-thin">{{ App\Helpers::formatPrice($order?->subtotal()) }} TL</h1>
                </div>
            </div>
        </div>
        @foreach ($order?->items ?? [] as $item)
            @php
                $product = $item->product;
            @endphp
            <div class="flex items-start justify-between gap-6 mb-2">
                <div class="flex items-start justify-between gap-4 w-full">
                    <div class="flex items-start gap-4">
                        @if ($product !== null)
                            <a
                                href="{{ route('products.show', ['category_slug' => $product->category->slug, 'product_slug' => $product->slug]) }}">
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-auto" alt="">
                            </a>
                        @else
                            <img src="{{ asset('storage/' . $item->product_image) }}" class="w-16 h-auto"
                                alt="">
                        @endif
                        <p class="flex items-center gap-2">
                            <span>{{ $item->quantity }}</span>
                            <svg class="w-5 h-5 mt-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                            </svg>
                            @if ($product !== null)
                                <a class="hover:underline"
                                    href="{{ route('products.show', ['category_slug' => $product->category->slug, 'product_slug' => $product->slug]) }}">{{ $product->name }}</a>
                            @else
                                <span class="line-through">{{ $item->product_name }}</span>

                                <h2 class="text-red-600 text-nowrap">Product deleted.</h2>
                            @endif
                        </p>
                    </div>
                    <p class="text-nowrap">{{ App\Helpers::formatPrice($item->subtotal()) }} TL</p>
                </div>
            </div>
        @endforeach
    </div>
</x-modal>
