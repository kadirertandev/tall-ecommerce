@php
    $product = $product?->product ?? $product;
@endphp

<swiper-slide class="flex flex-col justify-between gap-4 p-2 border-2 border-gray-100 shadow-xl">
    <div class="flex items-center justify-center flex-1 w-full">
        <a
            href="{{ route('products.show', ['category_slug' => $product->category->slug, 'product_slug' => $product->slug]) }}">
            <img src="{{ asset('storage/' . $product->image) }}" class="w-auto max-h-40" alt="">
        </a>
    </div>
    <div class="space-y-2">
        <h1 class="text-left line-clamp-2">{!! $product->title() !!}</h1>
        <div class="flex items-center gap-2">
            <div class="flex items-center">
                <x-stars :stars="$product->ratingAverage()" />
            </div>
            @if ($product->reviews()->count() >= 1)
                <h3 class="text-xs font-thin">({{ $product->ratingAverage() }})</h3>
            @endif
        </div>
        <div class="flex flex-wrap items-end justify-between">
            <div class="flex flex-col items-start">
                <h1 @class([
                    'text-3xl font-thin' => !$product->discount_amount,
                    'text-2xl text-[#9B9B9B] font-thin line-through decoration-2' =>
                        $product->discount_amount,
                ])>
                    {{ App\Helpers::formatPrice($product->price) }} TL
                </h1>
                @if ($product->discount_amount)
                    <h1 @class(['text-3xl font-medium text-green-500'])>
                        {{ App\Helpers::formatPrice($product->price - $product->discount_amount) }}
                        TL
                    </h1>
                @endif
            </div>
            <livewire:add-to-cart-button :key="$prefix . $product->id" :productId="$product->id" :svg="false"
                class="text-white bg-linear-to-r! from-teal-500! via-teal-600! to-teal-700! hover:bg-linear-to-br! focus:ring-2 focus:outline-hidden focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center" />
        </div>
    </div>
</swiper-slide>
