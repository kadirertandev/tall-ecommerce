<div x-data="addressModal"
    class="w-full mb-4 text-gray-500 bg-white rounded-lg text-medium dark:text-gray-400 dark:bg-gray-800">
    <div class="flex items-center justify-between gap-12 mb-4">
        <div class="flex items-center justify-between flex-1 px-3 rounded-lg bg-gray-50 ring-2 ring-gray-100!">
            <h1 class="text-3xl">{{ __('frontend.auth.dropdown-on-nav.addresses') }}</h1>
        </div>
        <div class="flex items-center justify-end">
            <button type="button" @click="$dispatch('open-modal', {name: 'new-address'})"
                class=" focus:outline-hidden text-white bg-teal-500 hover:bg-teal-700 focus:ring-2 focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Add new address
            </button>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        @forelse ($this->addresses as $address)
            <div wire:key='address-{{ $address->id }}' @class([
                'bg-gray-50 ring-2 ring-gray-100! rounded-lg shadow-xl p-3',
                'order-first' => $this->defaultAddress?->id == $address->id,
            ])>
                @if ($this->defaultAddress?->id == $address->id)
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold font-roboto">{{ $address->title }}</h1>
                        <h1 class="font-semibold text-orange-400 uppercase">Default Address</h1>
                    </div>
                @else
                    <h1 class="text-2xl font-semibold font-roboto">{{ $address->title }}</h1>
                @endif
                <p class="font-thin text-black font-roboto">{{ $address->neighborhood }}</p>
                <p class="font-thin text-black font-roboto">{{ $address->address_line }}</p>
                <p>{{ $address->district }} / {{ $address->city }}</p>
                <div class="flex items-center gap-3 mt-2">
                    <button wire:click="edit({{ $address->id }})" class="flex items-center group">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white group-hover:fill-teal-500" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                        </svg>
                        <span class="group-hover:text-teal-500">Edit</span>
                    </button>
                    <button wire:click="askDeleteAddress('{{ $address->id }}')" class="flex items-center group">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white group-hover:fill-main-red" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="group-hover:text-main-red">Delete</span>
                    </button>
                </div>

            </div>
        @empty
            <div class="flex items-center justify-between w-full px-3 rounded-lg sm:w-7/12 lg:w-9/12">
                <h1 class="my-2 text-xl">No address found.</h1>
            </div>
        @endforelse
    </div>

    <x-modals.address-modal name="new-address" type="add" />
    <x-modals.address-modal name="edit-address" type="edit" />

</div>


@assets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
@endassets
