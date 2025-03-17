<h5 class="flex items-center gap-1">
    <span class="text-gray-500">Show</span>

    <button id="dropdownDefaultButton123" data-dropdown-toggle="dropdown123"
        class="inline-flex items-center gap-4 p-1 text-sm font-medium text-center text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:outline-hidden focus:ring-gray-300 "
        type="button">{{ $this->perPage }} <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 4 4 4-4" />
        </svg>
    </button>

    <!-- per page dropdown menu -->
    <div id="dropdown123"
        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700"
        {{-- wire:ignore.self --}}>
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 *:cursor-pointer"
            aria-labelledby="dropdownDefaultButton123">
            @foreach ([5, 10, 25, 50, 100] as $perPage)
                <li @click="$wire.set('perPage',{{ $perPage }})"
                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 ">
                    {{ $perPage }}
                </li>
            @endforeach
            <li class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 ">
                <input wire:model.live.debounce.300ms='perPage' type="number" min="5"
                    value="{{ $this->perPage }}"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-600 focus:border-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            </li>
        </ul>
    </div>

    <span>entries</span>
</h5>
