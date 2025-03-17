@props(['category'])
<x-modal name="view-category" :title="$category?->name">
    <div class="p-4 space-y-2 md:p-5">
        <div class="flex items-stretch gap-4 mb-5">
            <img class="w-24 h-auto col-span-2" src="{{ asset('storage/' . $category?->image) }}">

            <div class="flex items-stretch gap-4">
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Name</h1>
                    <p class="font-light text-gray-500">{{ $category?->name }}</p>
                </div>
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Slug</h1>
                    <p class="font-light text-gray-500">{{ $category?->slug }}</p>
                </div>
            </div>
        </div>

        <div class="mb-5">
            <h1 class="mb-2 font-semibold leading-none text-gray-900">Brands</h1>
            <select
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @foreach ($category?->brands ?? [] as $brand)
                    <option wire:key='category-{{ $category?->id }}-brand-{{ $brand->id }}'>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-4 mb-5">
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Created At</h1>
                <p class="font-light text-gray-500">
                    {{ $category?->created_at->toDateTimeString() }}</p>
            </div>
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Created By</h1>
                <p class="font-light text-gray-500">
                    {{ $category?->createdBy?->full_name() ?? 'DATABASE SEEDER' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 mb-5">
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Last Updated At</h1>
                <p class="font-light text-gray-500">
                    {{ $category?->updated_at->toDateTimeString() }}</p>
            </div>
            <div>
                <h1 class="mb-2 font-semibold leading-none text-gray-900">Last Updated By</h1>
                <p class="font-light text-gray-500">
                    {{ $category?->updatedBy?->full_name() ?? 'NULL' }}</p>
            </div>
        </div>



        @if ($category?->deleted_at)
            <div class="flex items-center gap-4 mb-5">
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted At</h1>
                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                        {{ $category?->deleted_at->toDateTimeString() }}</p>
                </div>
                <div>
                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted By</h1>
                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                        {{ $category?->deletedBy?->full_name() ?? 'NULL' }}</p>
                </div>
            </div>
        @endif
        <div class="flex items-center justify-between">
            <a href="{{ route('category-slug', ['slug' => $category?->slug ?? 'x']) }}">View
                Category's Page</a>

            <button wire:click="showEditModal({{ $category?->id }})"
                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2"
                type="button">Edit</button>
        </div>
    </div>
</x-modal>
