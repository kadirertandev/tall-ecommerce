@props(['admin', 'editForm', 'roles'])
{{-- {{ isset($product) ? dd($product) : '' }} --}}
<div x-data="{ show: false }" x-show="show" x-on:open-admin-edit-modal.window="show = true"
    x-on:close-admin-edit-modal.window="show = false,$dispatch('admin-edit-modal-closed')" class="relative z-10"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" wire:ignore.self style="display: none;">
    <div class="fixed inset-0 bg-gray-500 opacity-40 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto ">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0 ">
            <div
                class="relative transform {{-- overflow-hidden --}} rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                {{-- modal content --}}
                <div class="bg-white" @click.outside="$dispatch('close-admin-edit-modal')">
                    {{-- header --}}
                    <div class="flex items-center justify-between p-4 border-b  dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Update - {{ $admin?->full_name() }}
                        </h3>

                        <button type="button" @click="$dispatch('close-admin-edit-modal')"
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
                                <label for="first_name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                    Name</label>
                                <input wire:model.blur='editForm.first_name' type="text" id="first_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('editForm.first_name')
                                    <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                        {{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="last_name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                    Name</label>
                                <input wire:model.blur='editForm.last_name' type="text" id="last_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('editForm.last_name')
                                    <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                        {{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                <input wire:model.blur='editForm.email' type="text" id="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('editForm.email')
                                    <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                        {{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="phone_number"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone
                                    Number</label>
                                <input wire:model.blur='editForm.phone_number' type="text" id="phone_number"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('editForm.phone_number')
                                    <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                        {{ $message }}</p>
                                @enderror
                            </div>
                            {{-- <div>
                                <label for="date_of_birth"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date of
                                    Birth</label>
                                <input wire:model.blur='editForm.date_of_birth' type="text" id="date_of_birth"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('editForm.date_of_birth')
                                    <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                        {{ $message }}</p>
                                @enderror
                            </div> --}}
                            <div>
                                <label for="date_of_birth"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('frontend.form.user-profile-update-form.date-of-birth') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </div>
                                    <input id="date_picker_123" wire:model='editForm.date_of_birth'
                                        {{-- datepicker
                                        datepicker-autohide datepicker-format="yyyy/mm/dd" --}} type="date"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Select date">
                                </div>

                                @error('editForm.date_of_birth')
                                    <p class="flex items-center gap-1 p-2 bg-red-500 text-white mt-1 rounded-lg text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                        </svg>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>
                            @if ($admin?->isNot(auth()->user()))
                                <div>
                                    <label for="role"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                    <select id="role" wire:model.blur='editForm.roleId'
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        @foreach ($roles as $role)
                                            <option @selected($role->id == $editForm->roleId) value="{{ $role->id }}">
                                                {{ $role->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('editForm.role')
                                        <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                            {{ $message }}</p>
                                    @enderror
                                </div>
                            @endif







                            {{-- <div class="sm:col-span-2 flex justify-between items-end gap-2">
                                @if (!$this->editForm->image)
                                    <div>
                                        <img class="col-span-2 w-24 h-auto"
                                            src="{{ asset('storage/' . $admin?->image) }}">
                                    </div>
                                @else
                                    @if (in_array($this->editForm->image->getClientOriginalExtension(), App\Constants\MimeTypes::ALLOWED_PHOTO_MIMES_PREVIEW))
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
                            </div> --}}
                        </div>
                        <div class="flex items-center space-x-4">
                            <button wire:click.prevent='update' type="submit"
                                class="text-white bg-teal-500 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                <span>Update admin</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
