@props(['product', 'categories', 'brands'])
<x-modal name="edit-product" :title="'Edit - ' . $product?->name">
    <form class="p-4 space-y-2" action="#">
        <div class="grid gap-4 mb-4 sm:grid-cols-2">
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                <input wire:model.blur='editForm.name' type="text" id="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                @error('editForm.name')
                    <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                        {{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="slug" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Slug</label>
                <input disabled wire:model.blur='editForm.slug' type="text" id="slug"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                @error('editForm.slug')
                    <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                        {{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="category"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                <select wire:model.live='editForm.category' id="category"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    @foreach ($this->categories as $category)
                        <option wire:key='category-{{ $category->id }}' @selected($category->is($product?->category))
                            value="{{ $category->id }}">
                            {{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
                <select wire:model.live='editForm.brand' id="brand"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    @foreach ($brands ?? [] as $brand)
                        <option wire:key='brand-{{ $brand->id }}' @selected($brand->is($product?->brand))
                            value="{{ $brand->id }}">
                            {{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                <input wire:model='editForm.price' type="number" value="399" id="price"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            </div>
            <div class="sm:col-span-2">
                <label for="description"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                <textarea wire:model.blur='editForm.description' id="description" rows="5"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"></textarea>
                @error('editForm.description')
                    <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                        {{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-end justify-between gap-2 sm:col-span-2">
                @if (!$this->editForm->image)
                    <div>
                        <img class="w-24 h-auto col-span-2" src="{{ asset('storage/' . $product?->image) }}">
                    </div>
                @else
                    @if (in_array(
                            $this->editForm->image->getClientOriginalExtension(),
                            App\Constants\MimeTypes::ALLOWED_PHOTO_MIMES_PREVIEW))
                        <div class="relative">
                            <img class="w-24 h-auto" src="{{ $this->editForm->image->temporaryUrl() }}">
                            <button wire:click.prevent='removeImage' class="absolute bottom-0 right-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24">
                                    <path fill="#ff0606"
                                        d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7zm4 12H8v-9h2zm6 0h-2v-9h2zm.618-15L15 2H9L7.382 4H3v2h18V4z" />
                                </svg>
                            </button>
                        </div>
                    @endif
                @endif


                <div class="flex-1">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload
                        file</label>
                    <input wire:model.live='editForm.image'
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-hidden dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="file_input_help" id="file_input" type="file">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">
                        {{ implode(', ', App\Constants\MimeTypes::ALLOWED_PHOTO_MIMES_UPLOAD) }}
                    </p>
                    @error('editForm.image')
                        <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                            {{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <button wire:click.prevent='update' type="submit"
                class="text-white bg-teal-500 hover:bg-primary-800 focus:ring-2 focus:outline-hidden focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <span>Update product</span>
            </button>
        </div>
    </form>
</x-modal>
