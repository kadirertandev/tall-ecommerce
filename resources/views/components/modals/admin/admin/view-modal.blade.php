@props(['admin'])
<x-modal name="view-admin" :title="$admin?->full_name()">
    <div class="p-4 space-y-2 md:p-5">

        <div class="flex items-start gap-2 mb-5">
            @if ($admin->profile_image ?? false)
                <img src="{{ asset('storage/' . $admin->profile_image) }}" class="w-24 h-auto rounded-md">
            @else
                <img src="{{ asset('storage/profile-admin.png') }}" class="w-24 h-auto rounded-md">
            @endif

            <div>
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div>
                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                        <h2 class="text-gray-400 text-md">First Name</h2>
                        <h2 class="text-md">{{ $admin?->first_name }}</h2>
                    </div>
                    <div>
                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                        <h2 class="text-gray-400 text-md">Last Name</h2>
                        <h2 class="text-md">{{ $admin?->last_name }}</h2>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div>
                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                        <h2 class="text-gray-400 text-md">Email</h2>
                        <h2 class="text-md">{{ $admin?->email }}</h2>
                    </div>
                    <div>
                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                        <h2 class="text-gray-400 text-md">Phone Number</h2>
                        <h2 class="text-md">{{ $admin?->phone_number ?? 'NULL' }}</h2>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div>
                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                        <h2 class="text-gray-400 text-md">Date of Birth</h2>
                        <h2 class="text-md">{{ $admin?->date_of_birth ?? 'NULL' }}</h2>
                    </div>
                    <div>
                        {{-- <h1 class="font-bold text-md">{{ $admin?->full_name() }}</h1> --}}
                        <h2 class="text-gray-400 text-md">Role</h2>
                        <h2 class="text-md">{{ $admin?->role()->name ?? 'NULL' }}</h2>
                    </div>
                </div>
            </div>
        </div>

        @if ($admin?->deleted_at)
            <div class="flex items-center gap-4 mb-5">
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

        <button wire:click="showEditModal({{ $admin?->id }})"
            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2"
            type="button">Edit</button>
    </div>
</x-modal>
