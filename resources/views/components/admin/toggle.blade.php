@props(['toggle', 'text'])
<label class="inline-flex items-center w-full p-2 cursor-pointer">
    <input wire:model.live="{{ $toggle }}" type="checkbox" class="sr-only peer">
    <div
        class="relative w-11 h-6 bg-gray-200! rounded-full peer!  peer-focus:ring-2! peer-focus:ring-teal-300! dark:peer-focus:ring-teal-800! peer-checked:after:translate-x-full! peer-checked:rtl:after:-translate-x-full! peer-checked:after:border-white! after:content-['']! after:absolute! after:top-0.5! after:start-[2px]! after:bg-white! after:border-gray-300! after:border! after:rounded-full! after:h-5! after:w-5! after:transition-all! dark:border-gray-600! peer-checked:bg-teal-600!">
    </div>
    <span class="text-sm font-medium! text-gray-900! ms-3!">{{ $text }}</span>
</label>
