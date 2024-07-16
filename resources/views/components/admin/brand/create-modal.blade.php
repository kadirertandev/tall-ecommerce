<div x-data="{ show: false }" x-show="show" x-on:open-brand-create-modal.window="show = true"
    x-on:close-brand-create-modal.window="show = false,$dispatch('brand-create-modal-closed')" class="relative z-10"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" wire:ignore.self style="display: none;">
    <div class="fixed inset-0 bg-gray-500 opacity-40 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                {{-- modal content --}}
                <div class="bg-white" @click.outside="$dispatch('close-brand-create-modal')" wire:ignore.self>
                    {{-- header --}}
                    <div class="flex items-center justify-between p-4 border-b  dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Create New Brand
                        </h3>

                        <button type="button" @click="$dispatch('close-brand-create-modal')"
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
                            <div class="col-span-2">
                                <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                <input wire:model.blur='createForm.name' type="text" name="name" id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('createForm.name')
                                    <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                        {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-2">
                                <label for="slug"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Slug</label>
                                <input disabled wire:model.blur='createForm.slug' type="text" name="slug"
                                    id="slug"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('createForm.slug')
                                    <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2 flex justify-between items-end gap-2">
                                @if ($this->createForm->image)
                                    @if (in_array(
                                            $this->createForm->image->getClientOriginalExtension(),
                                            App\Constants\MimeTypes::ALLOWED_PHOTO_MIMES_PREVIEW))
                                        <div class="relative">
                                            <img class="w-24 h-auto"
                                                src="{{ $this->createForm->image->temporaryUrl() }}">
                                            <button wire:click.prevent='removeImage' class="absolute bottom-0 right-0">
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
                                    <input wire:model.live='createForm.image'
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="file_input_help" id="file_input" type="file">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">
                                        {{ implode(', ', App\Constants\MimeTypes::ALLOWED_PHOTO_MIMES_UPLOAD) }}
                                    </p>
                                    @error('createForm.image')
                                        <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                            {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-1">
                            <button wire:click.prevent='create' type="submit"
                                class="flex-1 w-full flex justify-center items-center space-x-2 text-white bg-teal-500 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                <span wire:loading.remove wire:target='create'>Create brand</span>
                                <div wire:loading wire:target='create' role="status">
                                    <svg aria-hidden="true"
                                        class="inline w-6 h-6 text-gray-200 animate-spin dark:text-gray-600 fill-gray-600 dark:fill-gray-300"
                                        viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                            fill="currentColor" />
                                        <path
                                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                            fill="currentFill" />
                                    </svg>
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>

                            <button wire:click.prevent='resetCreateFormFields'
                                class="text-white bg-red-500 px-3 py-1.5 rounded-lg" data-popover-target="popover-hover"
                                data-popover-trigger="hover">
                                <svg xmlns="http://www.w3.org/2000/svg" wire:loading.class='animate-spin-reverse'
                                    wire:target='resetCreateFormFields'
                                    class="inline w-6 h-6 text-gray-200 dark:text-gray-600 fill-gray-600 dark:fill-gray-300"
                                    viewBox="0 0 21 21">
                                    <g fill="none" fill-rule="evenodd" stroke="currentColor"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3.578 6.487A8 8 0 1 1 2.5 10.5" />
                                        <path d="M7.5 6.5h-4v-4" />
                                    </g>
                                </svg>
                            </button>

                            <div data-popover id="popover-hover" role="tooltip"
                                class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-300 rounded-lg shadow-sm opacity-0 dark:text-gray-600 dark:border-gray-600 dark:bg-gray-800">
                                {{-- <div
                                    class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Popover hover</h3>
                                </div> --}}
                                <div class="px-3 py-2">
                                    <p>Reset form fields.</p>
                                </div>
                                <div data-popper-arrow></div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
