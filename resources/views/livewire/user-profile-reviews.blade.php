<div class=" bg-white text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full mb-4">
    <div class="bg-gray-50 ring-2 ring-gray-100 px-3 rounded-lg">
        <h1 class="text-3xl">Reviews</h1>
    </div>

    @forelse (auth()->user()->reviews as $review)
        <div class="bg-gray-100 rounded-lg p-3 my-4">
            <div class="flex items-start justify-between">
                <div class="flex gap-2 mb-4">
                    <div>
                        <img class="max-w-20 aspect-auto" src="{{ asset('storage/' . $review->product->image) }}"
                            alt="">
                    </div>
                    <div class="space-y-1">
                        <h1 class="font-semibold">
                            <a
                                href="{{ route('products.show', ['category_slug' => $review->product->category->slug, 'product_slug' => $review->product->slug]) }}">{{ $review->product->name }}</a>
                        </h1>
                        <div class="flex">
                            <x-stars :stars="$review->rating" />
                        </div>
                        <div>
                            <p data-popover-target="popover-{{ $review->id }}" class="cursor-pointer">
                                <span class="font-semibold">Status:</span>
                                <span class="font-thin">{{ Str::of($review->status->value)->headline() }}</span>
                            </p>

                            <div data-popover id="popover-{{ $review->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block min-w-min text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                <div
                                    class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Explanations</h3>
                                </div>
                                <div class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8" viewBox="0 0 16 16">
                                                <g fill="black">
                                                    <path
                                                        d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="">
                                            <h2 class="font-semibold">Evaluating</h2>
                                            <p>A comment editor will supervise your comment as soon as possible.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8" viewBox="0 0 24 24">
                                                <path fill="black"
                                                    d="M9 22c-.55 0-1-.45-1-1v-3H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12c0 1.11-.89 2-2 2h-6.1l-3.7 3.71c-.2.19-.45.29-.7.29zm1-6v3.08L13.08 16H20V4H4v12zm5.6-10L17 7.41L10.47 14L7 10.5l1.4-1.41l2.07 2.08z" />
                                            </svg>
                                        </div>
                                        <div class="">
                                            <h2 class="font-semibold">Approved</h2>
                                            <p>Your comment has been checked by an editor and published.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8" viewBox="0 0 24 24">
                                                <path fill="black"
                                                    d="M9.707 13.707L12 11.414l2.293 2.293l1.414-1.414L13.414 10l2.293-2.293l-1.414-1.414L12 8.586L9.707 6.293L8.293 7.707L10.586 10l-2.293 2.293z" />
                                                <path fill="black"
                                                    d="M20 2H4c-1.103 0-2 .897-2 2v18l5.333-4H20c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2m0 14H6.667L4 18V4h16z" />
                                            </svg>
                                        </div>
                                        <div class="">
                                            <h2 class="font-semibold">Rejected</h2>
                                            <p>Your comment has been rejected by the editor.</p>
                                        </div>
                                    </div>

                                </div>
                                <div data-popper-arrow></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <h1>{{ $review->created_at->toDayDateTimeString() }}</h1>
                    <h1>{{ $review->created_at->diffForHumans() }}</h1>
                </div>
            </div>
            <h1 class="font-semibold">{{ $review->title }}</h1>
            <p class="font-thin">{{ $review->comment }}</p>
        </div>
    @empty
        <div class="w-full sm:w-7/12 lg:w-9/12 flex items-center justify-between px-3 rounded-lg">
            <h1 class="text-xl my-2">No reviews found.</h1>
        </div>
    @endforelse

</div>
