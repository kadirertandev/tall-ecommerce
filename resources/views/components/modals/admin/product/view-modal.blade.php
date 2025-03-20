@props(['product'])
<x-modal name="view-product" :title="$product?->name">
    <div class="p-4 space-y-2 md:p-5">
        <div class="grid grid-cols-10 gap-4 mb-4">
            <img class="w-24 h-auto col-span-2" src="{{ asset('storage/' . $product?->image) }}">
            <div class="col-span-8">
                <h1 class="font-semibold text-md">{!! $product?->title() !!}</h1>
            </div>
        </div>
        <div class="mb-5">
            <h1 class="mb-2 font-semibold leading-none text-gray-900">Description</h1>
            <p class="mb-4 font-light text-gray-500 sm:mb-5">{{ $product?->description }}</p>
        </div>
        <div class="mb-5 flex {{-- items-start --}} gap-4 items-stretch">
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Category</h1>
                <p class="mb-4 font-light text-gray-500 sm:mb-5">{{ $product?->category->name }}</p>
            </div>
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Brand</h1>
                <p class="mb-4 font-light text-gray-500 sm:mb-5">{{ $product?->brand->name }}</p>
            </div>
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900 ">Price</h1>
                <p class="mb-4 font-light text-gray-500 sm:mb-5">
                    {{ App\Helpers::formatPrice($product?->price) }} TL</p>
            </div>
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Rating</h1>
                <div class="flex items-center">
                    <x-stars :stars="floor($product?->review_rating)" />
                    <span
                        class="ml-1 text-gray-500 dark:text-gray-400">{{ number_format($product?->review_rating, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4 mb-5">
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Last Updated At</h1>
                <p class="mb-4 font-light text-gray-500 sm:mb-5">
                    {{ $product?->updated_at->toDateTimeString() }}</p>
            </div>
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Last Updated By</h1>
                <p class="mb-4 font-light text-gray-500 sm:mb-5">
                    {{ $product?->updatedBy?->full_name() ?? 'NULL' }}</p>
            </div>
        </div>
        @if ($product?->deleted_at)
            <div class="flex items-center gap-4 mb-5">
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted At</h1>
                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                        {{ $product?->deleted_at->toDateTimeString() }}</p>
                </div>
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted By</h1>
                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                        {{ $product?->deletedBy?->full_name() ?? 'NULL' }}</p>
                </div>
            </div>
        @endif
        <div class="flex items-center justify-between">
            <a
                href="{{ route('products.show', ['category_slug' => $product?->category->slug ?? 'x', 'product_slug' => $product?->slug ?? 'x']) }}">View
                Product's Page</a>

            <button wire:click="showEditModal({{ $product?->id }})"
                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2"
                type="button">Edit</button>
        </div>
    </div>
</x-modal>
