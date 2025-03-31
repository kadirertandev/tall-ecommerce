@props(['title', 'step' => 1])
<div class="mb-4">
    <section class="py-4 antialiased bg-white dark:bg-gray-900">
        <div class="mx-auto max-w-(--breakpoint-xl)">
            <div class="flex gap-6">
                <div class="flex items-center justify-between w-8/12 ">
                    <h2 class="text-3xl font-thin text-gray-900">{{ $title }}</h2>
                    @if ($step == 1)
                        @if (count($this->cartItems) > 0)
                            <p>{{ __('frontend.cart.cart-total-items-message', ['x' => count($this->cartItems)]) }}</p>
                        @endif
                    @endif
                </div>
                <div class="w-4/12"></div>
            </div>

            @if ($step == 2)
                @if (count($this->addresses) == 0)
                    <button type="button" @click="$dispatch('open-modal', {name: 'new-address'})"
                        class="focus:outline-hidden text-white bg-green-700 hover:bg-green-800 focus:ring-2 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mt-4 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Add new address
                    </button>

                    <x-modals.address-modal name="new-address" type="add" />
                @endif
            @endif

            <div class="flex items-start gap-6 mt-6">
                <div class="w-8/12 mx-auto lg:max-w-2xl xl:max-w-4xl">

                    {{ $content }}

                </div>
                <div class="w-4/12 mx-auto space-y-6">
                    <div
                        class="p-4 space-y-4 bg-white border border-gray-200 rounded-lg shadow-xs dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-xl font-thin text-gray-900 text-nowrap">
                            Order
                            summary</p>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-500 text-nowrap dark:text-gray-400">
                                        Original price
                                    </dt>
                                    <dd class="text-base font-medium text-gray-900 dark:text-white">
                                        {{ App\Helpers::formatPrice($this->cart?->subtotal()) }} TL</dd>
                                </dl>
                            </div>

                            <dl
                                class="flex items-center justify-between gap-4 pt-2 border-t border-gray-200 dark:border-gray-700">
                                <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                                <dd class="text-base font-bold text-gray-900 dark:text-white">
                                    {{ App\Helpers::formatPrice($this->cart?->subtotal()) }} TL</dd>
                            </dl>
                        </div>

                        {{ $rightSideContent ?? '' }}

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
