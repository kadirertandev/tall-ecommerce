@props(['stars'])
{{-- {{ var_dump($stars) }} --}}
@if (is_int($stars))
    @for ($i = 1; $i <= $stars; $i++)
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
            <path fill="#faca15" d="M14.43 10L12 2l-2.43 8H2l6.18 4.41L5.83 22L12 17.31L18.18 22l-2.35-7.59L22 10z" />
        </svg>
    @endfor
    @for ($i = 1; $i <= 5 - $stars; $i++)
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24">
            <path fill="#d1d5db" d="M14.43 10L12 2l-2.43 8H2l6.18 4.41L5.83 22L12 17.31L18.18 22l-2.35-7.59L22 10z" />
        </svg>
    @endfor
@endif

@if (is_float($stars))
    @for ($i = 1; $i <= floor($stars); $i++)
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
            <path fill="#faca15" d="M14.43 10L12 2l-2.43 8H2l6.18 4.41L5.83 22L12 17.31L18.18 22l-2.35-7.59L22 10z" />
        </svg>
    @endfor
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24">
        <path fill="#faca15"
            d="m22 9.24l-7.19-.62L12 2L9.19 8.63L2 9.24l5.46 4.73L5.82 21L12 17.27L18.18 21l-1.63-7.03zM12 15.4V6.1l1.71 4.04l4.38.38l-3.32 2.88l1 4.28z" />
    </svg>
    @for ($i = 1; $i <= 5 - ceil($stars); $i++)
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24">
            <path fill="#d1d5db" d="M14.43 10L12 2l-2.43 8H2l6.18 4.41L5.83 22L12 17.31L18.18 22l-2.35-7.59L22 10z" />
        </svg>
    @endfor
@endif
