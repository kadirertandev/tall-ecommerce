@props(['title' => '', 'name'])
<div x-data="{ ...$store.modal, modalName: '{{ $name }}' }" x-show="visibleModals.has(modalName)" x-trap="visibleModals.size > 0"
    x-on:open-modal.window="visibleModals.add($event.detail.name)"
    x-on:close-modal.window="closeModal($event.detail.name)"
    x-on:modal-closed.window="console.log(`${$event.detail.modalName} modal closed`)"
    @keydown.escape.window="removeLastAddedModal(); $event.stopImmediatePropagation();" style="display: none;"
    class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500/75 opacity-40 transition-opacity" aria-hidden="true"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

            <div
                class='relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg'>

                <div class="bg-white" @click="$event.stopImmediatePropagation();"
                    @click.outside="removeLastAddedModal(); $event.stopImmediatePropagation();">
                    {{-- modal header start --}}
                    <div class="flex items-center justify-between p-4 border-b  dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $title }}
                        </h3>

                        <button type="button" @click.stop="closeModal(modalName)"
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
                    {{-- modal header end --}}

                    {{-- modal content start --}}
                    {{ $slot }}
                    {{-- modal content end --}}
                </div>
            </div>
        </div>
    </div>
</div>
