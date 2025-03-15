@props(['dropdownPlacement' => 'bottom'])
<div class="flex items-center justify-center">
    <button id="dropdownDefault" data-dropdown-toggle="dropdown" data-dropdown-placement="{{ $dropdownPlacement }}"
        class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-600 focus:ring-2 focus:ring-primary-300 focus:outline-hidden"
        type="button">
        <span @class(['order-2' => $dropdownPlacement === 'left'])>Filter</span>

        @if ($dropdownPlacement === 'left')
            <svg class="w-2.5 h-2.5 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 1 1 5l4 4" />
            </svg>
        @else
            <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        @endif
    </button>

    <!-- Dropdown menu -->
    <div id="dropdown" class="z-10 hidden w-56 p-2 bg-white rounded-lg shadow-sm" {{-- wire:ignore.self --}}>
        <div id="accordion-collapse" data-accordion="collapse">

            {{ $toggles ?? null }}

            {{ $footer ?? null }}
        </div>

    </div>
</div>
