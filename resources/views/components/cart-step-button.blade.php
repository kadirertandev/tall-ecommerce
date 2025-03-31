@props(['order', 'step', 'disabled' => true])
@php
    $stepData = match ($order) {
        1 => [
            'svgViewBox' => '0 0 16 16',
            'svgPath' =>
                '<path fill="currentColor" d="M14 13.1V12H4.6l.6-1.1l9.2-.9L16 4H3.7L3 1H0v1h2.2l2.1 8.4L3 13v1.5c0 .8.7 1.5 1.5 1.5S6 15.3 6 14.5S5.3 13 4.5 13H12v1.5c0 .8.7 1.5 1.5 1.5s1.5-.7 1.5-1.5c0-.7-.4-1.2-1-1.4" />',
            'title' => trans('frontend.cart.cart'),
        ],
        2 => [
            'svgViewBox' => '0 0 24 24',
            'svgPath' => '<path fill="currentColor"
                d="M12 6.5A2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5A2.5 2.5 0 0 1 9.5 9A2.5 2.5 0 0 1 12 6.5M12 2a7 7 0 0 1 7 7c0 5.25-7 13-7 13S5 14.25 5 9a7 7 0 0 1 7-7m0 2a5 5 0 0 0-5 5c0 1 0 3 5 9.71C17 12 17 10 17 9a5 5 0 0 0-5-5" />',
            'title' => trans('frontend.cart.address-selection'),
        ],
        3 => [
            'svgViewBox' => '0 0 24 24',
            'svgPath' =>
                '<path fill="currentColor" d="M3 19V5h18v14zM4 8.5h16V6H4zm11.775 5.594L20 10.525V9.5H4v1.725z" />',
            'title' => trans('frontend.cart.order-confirmation'),
        ],
    };
@endphp
<button wire:click="setStep({{ $order }}, 'step-button')" @disabled($disabled)
    @class([
        'inline-flex justify-between w-full p-2 text-gray-900 border-r border-gray-200 focus:ring-2 focus:ring-blue-300 active focus:outline-hidden',
        'bg-gray-300' => $step == $order,
        'bg-gray-100' => $step != $order,
        'rounded-s-lg' => $order == 1,
        'rounded-e-lg' => $order == 3,
    ]) aria-current="page">
    <div class="flex items-center gap-4 text-2xl">
        <svg xmlns="http://www.w3.org/2000/svg" @class([
            'w-10 h-10',
            'text-[#cbcaca]' => $step != $order,
            'text-white' => $step == $order,
        ]) viewBox="{{ $stepData['svgViewBox'] }}">
            {!! $stepData['svgPath'] !!}
        </svg>
        <span class="font-thin">{{ $stepData['title'] }}</span>
    </div>
    <div class="flex items-center gap-2">
        @if ($step > $order)
            <svg xmlns="http://www.w3.org/2000/svg" @class([
                'w-12 h-12',
                'text-[#cbcaca]' => $step != $order,
                'text-white' => $step == $order,
            ]) viewBox="0 0 15 15">
                <path fill="currentColor" fill-rule="evenodd" d="M14.707 3L5.5 12.207L.293 7L1 6.293l4.5 4.5l8.5-8.5z"
                    clip-rule="evenodd" />
            </svg>
        @endif
        <h1 @class([
            'font-roboto font-semibold text-6xl',
            'text-[#cbcaca]' => $step != $order,
            'text-white' => $step == $order,
        ])>
            {{ $order }}
        </h1>
    </div>
</button>
