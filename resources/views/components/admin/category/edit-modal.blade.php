@props(['category', 'brands', 'editform'])
{{-- {{ isset($product) ? dd($product) : '' }} --}}
<div x-data="{ show: false }" x-show="show" x-on:open-category-edit-modal.window="show = true"
    x-on:close-category-edit-modal.window="show = false,$dispatch('category-edit-modal-closed')" class="relative z-10"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" wire:ignore.self style="display: none;">
    <div class="fixed inset-0 bg-gray-500 opacity-40 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto ">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0 ">
            <div
                class="relative transform {{-- overflow-hidden --}} rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                {{-- modal content --}}
                <div class="bg-white" @click.outside="$dispatch('close-category-edit-modal')">
                    {{-- header --}}
                    <div class="flex items-center justify-between p-4 border-b  dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Update - {{ $category?->name }}
                        </h3>

                        <button type="button" @click="$dispatch('close-category-edit-modal')"
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


                            <div class="flex items-center">
                                <input wire:model.live='editForm.is_popular'
                                    id="category-{{ $category?->id }}-is-popular" type="checkbox"
                                    value="{{ $category?->is_popular }}" @checked($category?->is_popular)
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                    @change="$wire.updateCategoryIsPopular($el.checked)">
                                <label for="category-{{ $category?->id }}-is-popular"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Is
                                    Popular</label>
                            </div>



                            <div>
                                <button id="dropdownSearchButton" data-dropdown-toggle="dropdownSearch"
                                    data-dropdown-placement="top"
                                    class="w-full inline-flex items-center px-4 py-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:outline-none"
                                    type="button">
                                    <span>Brands</span>
                                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M9 5 5 1 1 5" />
                                    </svg>
                                </button>

                                <!-- Dropdown menu -->
                                <div id="dropdownSearch"
                                    class="z-50 hidden bg-white rounded-lg shadow-2xl w-60 dark:bg-gray-700"
                                    wire:ignore.self>
                                    <div class="p-3">
                                        <label for="input-group-search" class="sr-only">Search</label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 20 20">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                                </svg>
                                            </div>
                                            <input wire:model.live.debounce.400ms='editForm.searchCategoryBrand'
                                                type="text" id="input-group-search"
                                                class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Search brand">
                                            <button wire:click="$set('editForm.searchCategoryBrand','')"
                                                @class([
                                                    'absolute inset-y-0 rtl:inset-r-0 end-0 flex items-center pe-3',
                                                    'hidden' => strlen($editform->searchCategoryBrand) <= 0,
                                                ]) type="button">
                                                <svg class="w-4 h-4 text-red-500 hover:w-5 hover:h-5" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18 17.94 6M18 18 6.06 6" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownSearchButton">
                                        @foreach ($brands as $brand)
                                            <li wire:key='brand-{{ $brand->id }}'
                                                class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <input wire:model.live='editForm.categoryBrands'
                                                    id="checkbox-brand-{{ $brand->id }}" type="checkbox"
                                                    value="{{ $brand->id }}"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                                    @change="$wire.updateCategoryBrands({{ $brand->id }}, $el.checked)">
                                                <label for="checkbox-brand-{{ $brand->id }}"
                                                    class="w-full ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">
                                                    {{ $brand->name }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="sm:col-span-2 flex justify-between items-end gap-2">
                                @if (!$this->editForm->image)
                                    <div>
                                        <img class="col-span-2 w-24 h-auto"
                                            src="{{ asset('storage/' . $category?->image) }}">
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
                                <span>Update category</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
