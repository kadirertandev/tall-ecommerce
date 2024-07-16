<div class="flex items-center gap-4 mt-4  my-2 py-2">
    @if (auth()->user()->profile_image)
        @if (Storage::disk('public')->exists(auth()->user()->profile_image))
            <img class="w-10 h-10 rounded-full" src="{{ asset('/storage/' . auth()->user()->profile_image) }}"
                alt="">
        @endif
    @else
        <div
            class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
            <span
                class="font-medium text-gray-600 dark:text-gray-300">{{ auth()->user()->placeholder_initials() }}</span>
        </div>
    @endif

    <div class="font-medium text-black">
        <div>{{ auth()->user()->full_name() }}</div>
    </div>
</div>
