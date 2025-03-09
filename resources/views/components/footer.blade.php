<footer class="pb-2 text-white bg-main-red">
    <div class="text-center p-2 bg-[#c62f3e]">
        <a href="#body" class="block w-full text-md">{{ __('frontend.back-to-top') }}</a>
    </div>
    <div class="main-container">
        <a href="/">
            <h1 class="inline text-2xl font-roboto">
                <span class="font-extralight">eco</span><span class="font-extrabold">mmerce</span>
            </h1>
        </a>

        <div class="flex items-start justify-between gap-20">
            <p>{{ __('frontend.all-you-need-for-all-your-needs') }}</p>
            <div class="flex flex-col flex-1">
                <h1 class="mb-2 font-medium font-roboto">{{ __('frontend.corporate') }}</h1>
                <div class="flex flex-col *:font-roboto">
                    <a href="{{ route('aboutus') }}" class="hover:underline">{{ __('frontend.aboutus') }}</a>
                    <a href="{{ route('contact') }}" class="hover:underline">{{ __('frontend.contact') }}</a>
                </div>
            </div>

            <livewire:language-dropdown />
        </div>
    </div>
</footer>
