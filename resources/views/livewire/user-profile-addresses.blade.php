<div x-data="addressModal"
    class="bg-white text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full mb-4">
    <div class="flex items-center justify-between gap-12 mb-4">
        <div class="flex-1 flex items-center justify-between bg-gray-50 ring-2 ring-gray-100 px-3 rounded-lg">
            <h1 class="text-3xl">{{ __('frontend.auth.dropdown-on-nav.addresses') }}</h1>
        </div>
        <div class="flex items-center justify-end">
            <button type="button" @click="$dispatch('open-address-modal', {name: 'new-address'})"
                class=" focus:outline-none text-white bg-teal-500 hover:bg-teal-700 focus:ring-4 focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Add new address
            </button>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        @foreach ($this->addresses as $address)
            <div wire:key='address-{{ $address->id }}' @class([
                'bg-gray-50 ring-2 ring-gray-100 rounded-lg shadow-xl p-3',
                'order-first' => $this->defaultAddress?->id == $address->id,
            ])>
                @if ($this->defaultAddress?->id == $address->id)
                    <div class="flex items-center justify-between">
                        <h1 class="font-roboto font-semibold text-2xl">{{ $address->title }}</h1>
                        <h1 class="uppercase font-semibold text-orange-400">Default Address</h1>
                    </div>
                @else
                    <h1 class="font-roboto font-semibold text-2xl">{{ $address->title }}</h1>
                @endif
                <p class="font-roboto font-thin text-black">{{ $address->neighborhood }}</p>
                <p class="font-roboto font-thin text-black">{{ $address->address_line }}</p>
                <p>{{ $address->district }} / {{ $address->city }}</p>
                <div class="flex items-center gap-3 mt-2">
                    <button wire:click="edit({{ $address->id }})" class="group flex items-center">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white group-hover:fill-teal-500" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                        </svg>
                        <span class="group-hover:text-teal-500">Edit</span>
                    </button>
                    <button @click="$dispatch('delete-address-modal', {addressId: {{ $address->id }}})"
                        class="group flex items-center">
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
        @endforeach
    </div>

    <x-address-modal name="new-address" type="add" />
    <x-address-modal name="edit-address" type="edit" />

</div>


@assets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
@endassets

@script
    <script>
        let $modal = document.getElementById('address_modal');
        const options = {
            placement: 'bottom-left',
            backdrop: 'dynamic',
            backdropClasses: 'bg-gray-800/50 dark:bg-gray-900/80 fixed inset-0 z-40',
            closable: true,
            onHide: () => {
                console.log('modal is hidden');
            },
            onShow: () => {
                console.log('modal is shown');
            },
            onToggle: () => {
                console.log('modal has been toggled');
            },
        };
        const instanceOptions = {
            id: 'modalEl',
            override: true
        };
        let modal = new Modal($modal, options, instanceOptions);
        Alpine.data("addressModal", () => ({
            modal,
            showModal() {
                if (modal.isHidden()) {
                    $wire.editing = false;
                    setTimeout(() => {
                        modal.show()
                    }, 300);
                }
            },
            hideModal() {
                if (modal.isVisible()) {
                    modal.hide()
                }
            },
        }))
        Livewire.on("address-created", function() {
            modal.hide()
            Swal.fire({
                title: "Address added",
                icon: "success",
                iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 6.5A2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5A2.5 2.5 0 0 1 9.5 9A2.5 2.5 0 0 1 12 6.5M12 2a7 7 0 0 1 7 7c0 5.25-7 13-7 13S5 14.25 5 9a7 7 0 0 1 7-7m0 2a5 5 0 0 0-5 5c0 1 0 3 5 9.71C17 12 17 10 17 9a5 5 0 0 0-5-5"/></svg>`,
                timer: 1500,
                position: "center",
                customClass: {
                    icon: 'border-0',
                    popup: "mt-20 max-w-max	"
                },
                showConfirmButton: false,
                showCancelButton: false,
                width: "",
            })
        })

        Livewire.on("address-edit", function(event) {
            // console.log(event);
            // return;
            setTimeout(() => {
                $("#districts-select").val(event.selectedDistrict)
                $("#neighborhoods-select").val(event.selectedNeighborhood)
                $("#default-checkbox").prop("checked", event.makeDefault)
                modal.show();
            }, 300);
        })
    </script>
@endscript
