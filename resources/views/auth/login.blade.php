<x-header-meta />
<div class="min-h-screen">
    <x-header :nav1=false :nav2=false :nav3=false /> {{-- if any navs included activate my-16 in register-form.blade.php line2 div --}}

    <livewire:auth.login-form />
</div>

<x-footer />
<x-footer-meta>
    <x-slot:script>
        @yield('script')
    </x-slot>
</x-footer-meta>
