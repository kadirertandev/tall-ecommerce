@props(['product', 'rating'])
<x-modal name="user-profile-order-product-comment" title="Create Review">
    <div class="p-4 space-y-2 md:p-5">
        <div class="grid grid-cols-10 gap-4 mb-4">
            @if (Storage::disk('public')->exists($product?->image ?? 'null'))
                <img class="w-24 h-auto col-span-2" src="{{ asset('storage/' . $product?->image) }}">
            @endif
            <div class="col-span-8">
                <h1 class="font-semibold text-md">{!! $product?->title() !!}</h1>
            </div>
        </div>
        <div class="flex items-center gap-2 mb-5">
            <div id="" class="flex items-center">
                @for ($i = 1; $i <= 5; $i++)
                    <button wire:key='star-{{ $i }}' wire:ignore onclick="rate({{ $i }})"
                        wire:click='$set("rating",{{ $i }})'>
                        <svg id="star-{{ $i }}" class="w-6 h-6 text-gray-300 ms-2" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path
                                d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                        </svg>
                    </button>
                @endfor
            </div>
            <button wire:ignore id="btnClearRating" onclick="clearRate()" wire:click='$set("rating",0)'
                class="hidden px-2 py-1 text-xs text-white bg-red-500 rounded-lg">Temizle</button>
        </div>
        <form class="mb-6">
            <div class="mb-3">
                <input wire:model='reviewForm.title' type="text" id="default-input" placeholder="Write a title..."
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                @error('reviewForm.title')
                    <div class="p-2 mb-2 text-sm text-white bg-red-500 rounded-lg" role="alert">
                        <span class="font-medium">{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div class="px-4 py-2 mb-4 bg-white border border-gray-200 rounded-lg rounded-t-lg ">
                <label for="comment" class="sr-only">Your comment</label>
                <textarea wire:model='reviewForm.comment' id="comment" rows="6"
                    class="w-full px-0 text-sm text-gray-900 border-0 focus:ring-0 focus:outline-hidden dark:placeholder-gray-400 "
                    placeholder="Write a comment..." required></textarea>
            </div>
            @error('reviewForm.comment')
                <div class="p-2 mb-2 text-sm text-white bg-red-500 rounded-lg" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @enderror
            <button wire:click.prevent='createComment' type="submit"
                class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-teal-500! rounded-lg focus:ring-2 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-teal-600!">
                {{ __('frontend.post-comment') }}
            </button>
        </form>
    </div>
</x-modal>
<script>
    function rate(stars) {
        $("#btnClearRating").removeClass("hidden");
        for (let i = 1; i <= stars; i++) {
            $("#star-" + i).removeClass("text-gray-300")
            $("#star-" + i).addClass("text-yellow-300")
        }

        for (let i = 5; i > stars; i--) {
            $("#star-" + i).removeClass("text-yellow-300")
            $("#star-" + i).addClass("text-gray-300")
        }
    }

    function clearRate() {
        rate(0);
        $("#btnClearRating").addClass("hidden");
    }
</script>
