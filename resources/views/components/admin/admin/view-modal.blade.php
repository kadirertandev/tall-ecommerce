@props(['admin'])
<div x-data="{ show: false }" x-show="show" x-on:open-admin-view-modal.window="show = true"
    x-on:close-admin-view-modal.window="show = false,$dispatch('admin-view-modal-closed')" class="relative z-10"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" wire:ignore.self style="display: none;">
    <div class="fixed inset-0 bg-gray-500 opacity-40 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                {{-- modal content --}}
                <div class="bg-white" @click.outside="$dispatch('close-admin-view-modal')">
                    {{-- header --}}
                    <div class="flex items-center justify-between p-4 border-b  dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $admin?->full_name() }}
                        </h3>

                        <button type="button" @click="$dispatch('close-admin-view-modal')"
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
                    <div class="p-4 md:p-5 space-y-2">

                        <div class="mb-5 flex gap-2 items-start">
                            @if ($admin->profile_image ?? false)
                                <img src="{{ asset('storage/' . $admin->profile_image) }}"
                                    class="w-24 h-auto rounded-md">
                            @else
                                <img src="{{ asset('storage/profile-admin.png') }}" class="w-24 h-auto rounded-md">
                            @endif

                            <div>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <div>
                                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                                        <h2 class="text-md text-gray-400">First Name</h2>
                                        <h2 class="text-md">{{ $admin?->first_name }}</h2>
                                    </div>
                                    <div>
                                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                                        <h2 class="text-md text-gray-400">Last Name</h2>
                                        <h2 class="text-md">{{ $admin?->last_name }}</h2>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <div>
                                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                                        <h2 class="text-md text-gray-400">Email</h2>
                                        <h2 class="text-md">{{ $admin?->email }}</h2>
                                    </div>
                                    <div>
                                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                                        <h2 class="text-md text-gray-400">Phone Number</h2>
                                        <h2 class="text-md">{{ $admin?->phone_number ?? 'NULL' }}</h2>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <div>
                                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                                        <h2 class="text-md text-gray-400">Date of Birth</h2>
                                        <h2 class="text-md">{{ $admin?->date_of_birth ?? 'NULL' }}</h2>
                                    </div>
                                    <div>
                                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                                        <h2 class="text-md text-gray-400">Role</h2>
                                        <h2 class="text-md">{{ $admin?->role()->name ?? 'NULL' }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($admin?->deleted_at)
                            <div class="mb-5 flex items-center gap-4">
                                <div>
                                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted At</h1>
                                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                                        {{ $admin?->deleted_at->toDateTimeString() }}</p>
                                </div>
                                <div>
                                    <h1 class="mb-2 font-semibold leading-none text-gray-900">Deleted By</h1>
                                    <p class="mb-4 font-light text-gray-500 sm:mb-5">
                                        {{ $admin?->deletedBy?->full_name() ?? 'NULL' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
