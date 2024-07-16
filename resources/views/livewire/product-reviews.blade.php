<section id="reviews" class="bg-white  py-4  my-24 antialiased border-2 border-gray-50 shadow-2xl shadow-red-100">
    <div class="main-container px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">{{ __('frontend.reviews') }}
                ({{ $this->reviewsCount }})</h2>
        </div>
        <div class="flex items-center gap-2 mb-5">
            <div id="" class="flex items-center">
                @for ($i = 1; $i <= 5; $i++)
                    <button wire:key='star-{{ $i }}' wire:ignore onclick="rate({{ $i }})"
                        wire:click='$set("rating",{{ $i }})'>
                        <svg id="star-{{ $i }}" class="w-6 h-6 ms-2 text-gray-300" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path
                                d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                        </svg>
                    </button>
                @endfor
            </div>
            <h1>{{ $rating }}</h1>
            <button wire:ignore id="btnClearRating" onclick="clearRate()" wire:click='$set("rating",0)'
                class="hidden text-xs bg-red-500 px-2 py-1 text-white rounded-lg">Temizle</button>
        </div>
        <form class="mb-6">
            <div class="mb-3">
                <input wire:model='title' type="text" id="default-input" placeholder="Write a title..."
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                @error('title')
                    <div class="p-2 mb-2 text-sm bg-red-500 text-white rounded-lg" role="alert">
                        <span class="font-medium">{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 ">
                <label for="comment" class="sr-only">Your comment</label>
                <textarea wire:model='comment' id="comment" rows="6"
                    class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none  dark:placeholder-gray-400 "
                    placeholder="Write a comment..." required></textarea>
            </div>
            @error('comment')
                <div class="p-2 mb-2 text-sm bg-red-500 text-white rounded-lg" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @enderror
            <button wire:click.prevent='create' type="submit"
                class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-teal-500 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-teal-600">
                {{ __('frontend.post-comment') }}
            </button>
        </form>

        <div id="reviews-pagination">{{ $this->reviews()->links(data: ['scrollTo' => '#reviews-pagination']) }}</div>
        @foreach ($this->reviews() as $review)
            <article wire:key='review-{{ $review->id }}' id="review-{{ $review->id }}"
                class="my-4 border-s-2 border-b-2 border-red-200 ps-4 py-4 shadow-sm shadow-red-300">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center mb-4">
                        @if ($review->user->profile_image)
                            <img class="me-4 w-14 h-14 rounded-full"
                                src="{{ asset('storage/' . $review->user->profile_image) }}" alt="Michael Gough">
                        @else
                            <img class="me-4 w-14 h-14 rounded-full"
                                src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" alt="Michael Gough">
                        @endif
                        <div class="font-medium" wire:key='popover-container-{{ $review->id }}'>
                            <p wire:key='popover-{{ $review->id }}'
                                data-popover-target="popover-right{{ $review->id }}" data-popover-placement="right"
                                type="button">
                                {{ $review->user->first_name . ' ' . $review->user->last_name }} <time
                                    datetime="2014-08-16 19:00"
                                    class="block text-sm text-gray-500 dark:text-gray-400">Joined on
                                    {{ date_format($review->user->created_at, 'F Y') }}</time>
                            </p>

                            <div wire:key='popover-content-{{ $review->id }}' data-popover
                                id="popover-right{{ $review->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                <div
                                    class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Exact Join Date</h3>
                                </div>
                                <div class="px-3 py-2">
                                    <p>{{ $review->user->created_at->toDateString() }}</p>
                                </div>
                                <div data-popper-arrow></div>
                            </div>

                        </div>
                    </div>

                    <div wire:key='dropdown-container-{{ $review->id }}'>
                        <button id="dropdownComment{{ $review->id }}Button"
                            data-dropdown-toggle="dropdownComment{{ $review->id }}"
                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-400 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                            type="button">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 16 3">
                                <path
                                    d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                            </svg>
                            <span class="sr-only">Comment settings</span>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="dropdownComment{{ $review->id }}"
                            class="hidden z-10 w-36 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="dropdownMenuIconHorizontalButton">
                                <li wire:click='edit({{ $review->id }})' class="cursor-pointer">
                                    <a
                                        class="inline-flex w-full items-center gap-2 py-2 px-4 hover:bg-blue-100 hover:text-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                        <span>Edit</span></a>
                                </li>
                                <li wire:click='delete({{ $review->id }})' class="cursor-pointer">
                                    <a
                                        class="inline-flex w-full items-center gap-2  py-2 px-4 hover:bg-red-100 hover:text-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18 18 6M6 6l12 12" />
                                        </svg><span>Remove</span></a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="inline-flex w-full items-center gap-2 py-2 px-4 hover:bg-yellow-100 hover:text-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                        </svg>
                                        <span>Report</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="flex items-center mb-1 space-x-1 rtl:space-x-reverse">
                    <x-stars :stars="$review->rating" />
                    <h3 class="ms-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $review->title }}</h3>
                </div>
                <footer class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    {{-- <p>Reviewed in the United Kingdom on <time datetime="2017-03-03 19:00">March 3, 2017</time>
                    </p> --}}
                    {{-- <p>Reviewed on {{ $review->created_at }}</p> --}}
                    {{-- <p>Reviewed on {{ $review->created_at->toDateString() }}</p> --}}
                    {{-- <p>Reviewed on {{ $review->created_at->toTimeString() }}</p> --}}
                    {{-- <p>Reviewed on {{ $review->created_at->toDateTimeString() }}</p> --}}
                    <p>Reviewed on {{ $review->created_at->toFormattedDateString() }}</p>
                    {{-- <p>Reviewed on {{ $review->created_at->toDayDateTimeString() }}</p> --}}
                    {{-- <p>{{ Carbon\Carbon::now()->addMonths(3)->addHours(4) }}</p> --}}
                </footer>
                <p class="mb-2 text-gray-500 dark:text-gray-400">{{ $review->comment }}</p>
                {{-- <p class="mb-3 text-gray-500 dark:text-gray-400"></p> --}}
                <a href="#"
                    class="block mb-5 text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">Read
                    more</a>
                <aside>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">19 people found this helpful</p>
                    <div class="flex items-center mt-3">
                        <a href="#"
                            class="px-2 py-1.5 text-xs font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Helpful</a>
                        <a href="#"
                            class="ps-4 text-sm font-medium text-blue-600 hover:underline dark:text-blue-500 border-gray-200 ms-4 border-s md:mb-0 dark:border-gray-600">Report
                            abuse</a>
                    </div>
                </aside>
            </article>
        @endforeach
    </div>
</section>

@script
    <script>
        Livewire.on("product-reviews-page-updated", function() {
            setTimeout(() => {
                initFlowbite();
                console.log("flowbite initialized")
            }, 300);
        })
    </script>
@endscript
