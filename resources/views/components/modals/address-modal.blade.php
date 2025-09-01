@props(['name', 'type'])
<x-modal :$name :title="$type == 'add' ? 'Add new address' : 'Edit address'">
    <div class="p-4 space-y-2 md:p-5">
        <div>
            <label for="first_name" class="block mb-2 text-sm font-medium text-gray-600 dark:text-white">Address
                Title</label>
            <input wire:model.blur='form.addressTitle' type="text" id="first_name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 placeholder-gray-400! dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="John" required />
            @error('form.addressTitle')
                <div class="p-2 mb-2 text-sm text-white bg-red-500 rounded-lg" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>
        <div>
            <label for="cities-select" class="block text-sm font-medium text-gray-600 dark:text-white">City</label>
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
                <div class="p-2 mb-2 text-sm text-white bg-red-500 rounded-lg" role="alert">
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
                <div class="p-2 mb-2 text-sm text-white bg-red-500 rounded-lg" role="alert">
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
                <div class="p-2 mb-2 text-sm text-white bg-red-500 rounded-lg" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>
        <div>
            <label for="message" class="block mb-2 text-sm font-medium text-gray-600 dark:text-white">Cadde
                / Sokak / Apartman / Daire No</label>
            <textarea wire:model.blur='form.addressLine' id="message" rows="4"
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 placeholder-gray-400! dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Write your thoughts here..."></textarea>
            @error('form.addressLine')
                <div class="p-2 mb-2 text-sm text-white bg-red-500 rounded-lg" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>
        <div class="flex items-center mb-4">
            <input wire:model='form.makeDefault' id="default-checkbox123" type="checkbox"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="default-checkbox123" class="text-sm font-medium text-gray-900 ms-2 dark:text-gray-300">Make
                default
                address</label>
        </div>

        @if ($type == 'add')
            <button type="button" wire:click.prevent='create'
                class="w-full text-white bg-linear-to-r! from-teal-400! via-teal-500! to-teal-600! hover:bg-linear-to-br! focus:ring-2 focus:outline-hidden focus:ring-teal-300 dark:focus:ring-teal-800 shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">Add
                to my addresses</button>
        @else
            <button type="button" wire:click.prevent='update'
                class="w-full text-white bg-linear-to-r! from-teal-400! via-teal-500! to-teal-600! hover:bg-linear-to-br! focus:ring-2 focus:outline-hidden focus:ring-teal-300 dark:focus:ring-teal-800 shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80 font-medium rounded-lg text-md px-5 py-2.5 text-center me-2 mb-2">Update</button>
        @endif
    </div>
</x-modal>
