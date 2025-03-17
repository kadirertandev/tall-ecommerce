@props(['review', 'editform'])
<x-modal name="edit-review" :title="'Edit Review by ' . $review?->user->full_name() . ' for ' . $review?->product->name">
    <form class="p-4 space-y-2" action="#">
        {{-- <div class="grid gap-4 mb-4 sm:grid-cols-2"> --}}
        <div>
            <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
            <input wire:model='editForm.title' type="text" id="title"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            @error('editForm.title')
                <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                    {{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="comment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comment</label>
            <textarea wire:model='editForm.comment' type="text" id="comment"
                class="min-h-32 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </textarea>
            @error('editForm.comment')
                <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                    {{ $message }}</p>
            @enderror
        </div>
        {{-- </div> --}}
        <div class="flex items-center justify-between space-x-4">
            <button wire:click.prevent='update' type="submit"
                class="text-white bg-teal-500 hover:bg-primary-800 focus:ring-2 focus:outline-hidden focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <span>Update review</span>
            </button>
        </div>
    </form>
</x-modal>
