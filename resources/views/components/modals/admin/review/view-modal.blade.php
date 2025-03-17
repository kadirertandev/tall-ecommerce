@props(['review'])
<x-modal name="view-review" :title="'Review by ' . $review?->user->full_name() . ' for ' . $review?->product->name">
    <div class="p-4 space-y-2 md:p-5">
        <div class="flex items-stretch gap-4 mb-5">
            <img class="w-24 h-auto col-span-2" src="{{ asset('storage/' . $review?->product->image) }}">

            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">{{ $review?->product->name }}
                </h1>
                <div class="flex items-center">
                    <x-stars :stars="$review?->rating" />
                </div>
                <p>
                    <span class="font-semibold">Status:</span>
                    <span class="font-thin">{{ Str::of($review?->status->value)->headline() }}</span>
                </p>
                <h1>
                    {{ $review?->created_at->toDayDateTimeString() }} -
                    {{ $review?->created_at->diffForHumans() }}
                </h1>
            </div>
        </div>

        {{-- <div class="flex items-center gap-4 mb-5">
        <div> --}}
        <h1 class="mb-2 font-semibold leading-none text-gray-900">Title</h1>
        <p class="font-light text-gray-500">
            {{ $review?->title }}</p>
        <h1 class="mb-2 font-semibold leading-none text-gray-900">Comment</h1>
        <p class="font-light text-gray-500">
            {{ $review?->comment }}</p>
        {{-- </div>
    </div> --}}
        <div class="flex items-center gap-4 mb-5">
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Last Updated At</h1>
                <p class="font-light text-gray-500">
                    {{ $review?->updated_at->toDateTimeString() }}</p>
            </div>
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Last Updated By</h1>
                <p class="font-light text-gray-500">
                    {{ $review?->updatedBy?->full_name() ?? 'NULL' }}</p>
            </div>
        </div>



        @if ($review?->deleted_at)
            <div class="flex items-center gap-4 mb-5">
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted At</h1>
                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                        {{ $review?->deleted_at->toDateTimeString() }}</p>
                </div>
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted By</h1>
                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                        {{ $review?->deletedBy?->full_name() ?? 'NULL' }}</p>
                </div>
            </div>
        @endif

        @if ($review?->status == App\Enums\ReviewStatusType::APPROVED && !$review?->deleted_at)
            <div class="flex items-center justify-between">
                <button wire:click='viewReviewOnPage({{ $review?->id }})'>
                    View Review On Page
                </button>

                <button wire:click="showEditModal({{ $review?->id }})"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2"
                    type="button">Edit</button>
            </div>
        @endif
    </div>
</x-modal>
