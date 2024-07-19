@php
    $deal = $deal?->product ?? $deal;
@endphp

<swiper-slide class="flex flex-col justify-between gap-4 p-2 border-2 border-gray-100 shadow-xl">
    <div class="flex items-center justify-center flex-1 w-full">
        <a
            href="{{ route('products.show', ['category_slug' => $deal->category->slug, 'product_slug' => $deal->slug]) }}">
            <img src="{{ asset('storage/' . $deal->image) }}" class="w-auto max-h-40" alt="">
        </a>
    </div>
    <div class="space-y-2">
        <h1 class="text-left line-clamp-2">{!! $deal->title() !!}</h1>
        <div class="flex items-center gap-2">
            <div class="flex items-center">
                <x-stars :stars="$deal->ratingAverage()" />
            </div>
            @if ($deal->reviews()->count() >= 1)
                <h3 class="text-xs font-thin">({{ $deal->ratingAverage() }})</h3>
            @endif
        </div>
        <div class="flex flex-wrap items-end justify-between">
            <div class="flex flex-col items-start">
                <h1 @class([
                    'text-3xl font-thin' => !$deal->discount_amount,
                    'text-2xl text-[#9B9B9B] font-thin line-through decoration-2' =>
                        $deal->discount_amount,
                ])>
                    {{ App\Helpers::formatPrice($deal->price) }} TL
                </h1>
                @if ($deal->discount_amount)
                    <h1 @class(['text-3xl font-medium text-green-500'])>
                        {{ App\Helpers::formatPrice($deal->price - $deal->discount_amount) }}
                        TL
                    </h1>
                @endif
            </div>
            <livewire:add-to-cart-button :key="$prefix . $deal->id" :product_slug="$deal->slug" :svg="false"
                class="text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center" />
        </div>
    </div>
</swiper-slide>
