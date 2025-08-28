<div>
    @if ($this->type === 'nav')
        <button type="button" wire:click='askRemoveFromCart'
            class="font-medium text-red-400! hover:bg-gray-100 hover:text-red-600! p-2 rounded-lg">{{ __('frontend.cart.remove') }}</button>
    @elseif ($type === 'cart_step_one')
        <button type="button" wire:click='askRemoveFromCart'
            class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
            <svg class="me-1.5 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18 17.94 6M18 18 6.06 6" />
            </svg>
            <span>{{ __('frontend.cart.remove') }}</span>
        </button>
    @endif
</div>
