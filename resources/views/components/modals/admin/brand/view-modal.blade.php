@props(['brand'])
<x-modal name="view-brand" :title="$brand?->name">
    <div class="p-4 space-y-2 md:p-5">
        <div class="flex items-stretch gap-4 mb-5">
            <img class="w-24 h-auto col-span-2" src="{{ asset('storage/' . $brand?->image) }}">

            <div class="flex items-stretch gap-4">
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Name</h1>
                    <p class="font-light text-gray-500">{{ $brand?->name }}</p>
                </div>
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Slug</h1>
                    <p class="font-light text-gray-500">{{ $brand?->slug }}</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 mb-5">
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Created At</h1>
                <p class="font-light text-gray-500">
                    {{ $brand?->created_at?->toDateTimeString() ?? 'NULL' }}</p>
            </div>
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Created By</h1>
                <p class="font-light text-gray-500">
                    {{ $brand?->createdBy?->full_name() ?? 'DATABASE SEEDER' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 mb-5">
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Last Updated At</h1>
                <p class="font-light text-gray-500">
                    {{ $brand?->updated_at?->toDateTimeString() ?? 'NULL' }}</p>
            </div>
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Last Updated By</h1>
                <p class="font-light text-gray-500">
                    {{ $brand?->updatedBy?->full_name() ?? 'NULL' }}</p>
            </div>
        </div>



        @if ($brand?->deleted_at)
            <div class="flex items-center gap-4 mb-5">
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted At</h1>
                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                        {{ $brand?->deleted_at->toDateTimeString() }}</p>
                </div>
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted By</h1>
                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                        {{ $brand?->deletedBy?->full_name() ?? 'NULL' }}</p>
                </div>
            </div>
        @endif
        <div class="flex items-center justify-between">
            <a href="{{ route('brand-slug', ['slug' => $brand?->slug ?? 'x']) }}">View
                Brand's Page</a>

            <button wire:click="showEditModal({{ $brand?->id }})"
                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2"
                type="button">Edit</button>
        </div>
    </div>
</x-modal>
