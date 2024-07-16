@props(['name', 'type', 'address'])
<div x-data="{ show: false, name: '{{ $name }}' }" x-show="show" x-on:open-address-modal.window="show = ($event.detail.name === name)"
    x-on:close-address-modal.window="show = false,$dispatch('address-modal-closed')" class="relative z-10"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" wire:ignore.self style="display: none;">
    <div class="fixed inset-0 bg-gray-500 opacity-40 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                {{-- modal content --}}
                <div class="bg-white" @click.outside="$dispatch('close-address-modal')">
                    {{-- header --}}
                    <div class="flex items-center justify-between p-4 border-b  dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $type == 'add' ? 'Add new address' : 'Edit address' }}
                        </h3>

                        <button type="button" @click="$dispatch('close-address-modal')"
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
                        <div>
                            <label for="first_name"
                                class="block mb-2 text-sm font-medium text-gray-600 dark:text-white">Address
                                Title</label>
                            <input wire:model.blur='form.addressTitle' type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="John" required />
                            @error('form.addressTitle')
                                <div class="p-2 mb-2 text-sm bg-red-500 text-white rounded-lg" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="cities-select"
                                class="block text-sm font-medium text-gray-600 dark:text-white">City</label>
                            <select wire:model.live='form.selectedCity' id="cities-select"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Select City</option>
                                @foreach ($this->cities as $city)
                                    <option wire:key='city-{{ $city }}' value="{{ $city }}">
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form.selectedCity')
                                <div class="p-2 mb-2 text-sm bg-red-500 text-white rounded-lg" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div>
                            <label for="districts-select"
                                class="block text-sm font-medium text-gray-600 dark:text-white ">District</label>
                            <select wire:model.live='form.selectedDistrict' id="districts-select"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">>
                                <option value="asdff">Select District</option>
                                @foreach ($this->districts as $district)
                                    <option @selected($this->form->selectedDistrict == $district) wire:key='district-{{ $district }}'
                                        value="{{ $district }}">
                                        {{ $district }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form.selectedDistrict')
                                <div class="p-2 mb-2 text-sm bg-red-500 text-white rounded-lg" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div>
                            <label for="neighborhoods-select"
                                class="block text-sm font-medium text-gray-600 dark:text-white">Neighborhood</label>
                            <select wire:model.live='form.selectedNeighborhood' id="neighborhoods-select"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">>
                                <option value="asdf">Select City</option>
                                @foreach ($this->neighborhoods as $neighborhood)
                                    <option @selected($this->form->selectedNeighborhood == $neighborhood) wire:key='neighborhood-{{ $neighborhood }}'
                                        value="{{ $neighborhood }}">
                                        {{ $neighborhood }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form.selectedNeighborhood')
                                <div class="p-2 mb-2 text-sm bg-red-500 text-white rounded-lg" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="message"
                                class="block mb-2 text-sm font-medium text-gray-600 dark:text-white">Cadde
                                / Sokak / Apartman / Daire No</label>
                            <textarea wire:model.blur='form.addressLine' id="message" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Write your thoughts here..."></textarea>
                            @error('form.addressLine')
                                <div class="p-2 mb-2 text-sm bg-red-500 text-white rounded-lg" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="flex items-center mb-4">
                            <input wire:model='form.makeDefault' id="default-checkbox123" type="checkbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="default-checkbox123"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Make default
                                address</label>
                        </div>

                        @if ($type == 'add')
                            <button type="button" wire:click.prevent='add'
                                class="w-full text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">Add
                                to my addresses</button>
                        @else
                            <button type="button" wire:click.prevent='update'
                                class="w-full text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">Update</button>
                        @endif
                    </div>
                </div>
                {{-- <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button"
                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Deactivate</button>
                    <button type="button"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                </div> --}}
            </div>
        </div>
    </div>
</div>
