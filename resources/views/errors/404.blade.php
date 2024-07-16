<x-header-meta />
<div class="min-h-screen">
    <x-header :nav1=false :nav2=false :nav3=false /> {{-- if any navs included activate my-16 in register-form.blade.php line2 div --}}
    <main class="grid min-h-full place-items-center bg-white px-6 py-24 sm:py-32 lg:px-8">
        <div class="text-center">
            <p class="text-9xl font-semibold text-indigo-600">404</p>
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                {{ __('frontend.errors.404.page-not-found') }}</h1>
            <p class="mt-6 text-base leading-7 text-gray-600">{{ __('frontend.errors.404.couldnt-find') }}</p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="{{ route('home') }}"
                    class="rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('frontend.errors.404.go-back-home') }}</a>
            </div>
        </div>
    </main>

</div>
<x-footer-meta>
    <x-slot:script></x-slot:script>
</x-footer-meta>
