@props(['product', 'categories', 'brands'])
{{-- {{ isset($product) ? dd($product) : '' }} --}}
<div x-data="{ show: false }" x-show="show" x-on:open-product-edit-modal.window="show = true"
    x-on:close-product-edit-modal.window="show = false,$dispatch('product-edit-modal-closed')" class="relative z-10"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" wire:ignore.self style="display: none;">
    <div class="fixed inset-0 bg-gray-500 opacity-40 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto ">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0 ">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                {{-- modal content --}}
                <div class="bg-white" @click.outside="$dispatch('close-product-edit-modal')">
                    {{-- header --}}
                    <div class="flex items-center justify-between p-4 border-b  dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Update - {{ $product?->name }}
                        </h3>

                        <button type="button" @click="$dispatch('close-product-edit-modal')"
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
                    <div class="hidden p-4 md:p-5 space-y-2">
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
                                    {{ $product?->updated_by?->name ?? 'NULL' }}</p>
                            </div>
                        </div>
                    </div>
                    <form class="p-4 space-y-2" action="#">
                        <div class="grid gap-4 mb-4 sm:grid-cols-2">
                            <div>
                                <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                <input wire:model.blur='editForm.name' type="text" id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('editForm.name')
                                    <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                        {{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="slug"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Slug</label>
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
                                    @foreach ($categories as $category)
                                        <option wire:key='category-{{ $category->id }}' @selected($category->is($product?->category))
                                            value="{{ $category->id }}">
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="brand"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
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
                                <label for="price"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
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
                            <div class="sm:col-span-2 flex justify-between items-end gap-2">
                                @if (!$this->editForm->image)
                                    <div>
                                        <img class="col-span-2 w-24 h-auto"
                                            src="{{ asset('storage/' . $product?->image) }}">
                                    </div>
                                @else
                                    @if (in_array(
                                            $this->editForm->image->getClientOriginalExtension(),
                                            App\Constants\MimeTypes::ALLOWED_PHOTO_MIMES_PREVIEW))
                                        <div class="relative">
                                            <img class="w-24 h-auto"
                                                src="{{ $this->editForm->image->temporaryUrl() }}">
                                            <button wire:click.prevent='removeImage'
                                                class="absolute bottom-0 right-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8"
                                                    viewBox="0 0 24 24">
                                                    <path fill="#ff0606"
                                                        d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7zm4 12H8v-9h2zm6 0h-2v-9h2zm.618-15L15 2H9L7.382 4H3v2h18V4z" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                @endif


                                <div class="flex-1">
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                        for="file_input">Upload file</label>
                                    <input wire:model.live='editForm.image'
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
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
                                class="text-white bg-teal-500 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                <span>Update product</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
