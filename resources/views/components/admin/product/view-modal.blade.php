@props(['product'])
{{-- {{ isset($product) ? dd($product) : '' }} --}}
<div x-data="{ show: false }" x-show="show" x-on:open-product-view-modal.window="show = true"
    x-on:close-product-view-modal.window="show = false,$dispatch('product-view-modal-closed')" class="relative z-10"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" wire:ignore.self style="display: none;">
    <div class="fixed inset-0 bg-gray-500 opacity-40 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                {{-- modal content --}}
                <div class="bg-white" @click.outside="$dispatch('close-product-view-modal')">
                    {{-- header --}}
                    <div class="flex items-center justify-between p-4 border-b  dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $product?->name }}
                        </h3>

                        <button type="button" @click="$dispatch('close-product-view-modal')"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    {{-- body --}}
                    <div class="p-4 md:p-5 space-y-2">
                        <div class="mb-4 grid grid-cols-10 gap-4">
                            <img class="col-span-2 w-24 h-auto" src="{{ asset('storage/' . $product?->image) }}">
                            <div class="col-span-8">
                                <h1 class="text-md font-semibold">{!! $product?->title() !!}</h1>
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
                                    <x-stars :stars="$product?->ratingAverage()" />
                                    <span
                                        class="ml-1 text-gray-500 dark:text-gray-400">{{ $product?->ratingAverage() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5 flex items-center gap-4">
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
                            <div class="mb-5 flex items-center gap-4">
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
                        <div class="mb-5">
                            <a
                                href="{{ route('products.show', ['category_slug' => $product?->category->slug ?? 'x', 'product_slug' => $product?->slug ?? 'x']) }}">View
                                Product's Page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
