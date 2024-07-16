<section>
    <div
        class="min-h-screen flex flex-col items-center justify-center px-6 py-8 mx-auto my-4 {{-- my-16 --}} lg:py-0">
        <a href="/" class="text-4xl main-red mb-3">
            <span class="font-extralight">eco</span><span class="font-extrabold">mmerce</span>
        </a>
        <div class="w-full bg-white rounded-lg shadow-xl sm:max-w-md xl:p-0 dark:bg-gray-800 border-2 border-gray-100">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 ">
                <h1 class="text-2xl font-thin leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    {{ __('frontend.form.login-form.forgot-password') }}
                </h1>
                <form class="space-y-4 md:space-y-6">
                    {{-- @if (session('reset-password-mail-sent'))
                        <div class="bg-teal-500 text-white px-4 py-2">
                            Reset password mail is sent.
                        </div>
                    @endif --}}
                    {{-- <div>
                        <label for="email"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('frontend.form.login-form.your-email') }}</label>
                        <input wire:model.blur='email' type="email" name="email" id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="name@company.com" required="">
                        @error('email')
                            <p class="flex items-center gap-1 p-2 bg-red-500 text-white mt-1 rounded-lg text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div> --}}

                    <div class="relative z-0">
                        <input wire:model.blur='email' type="text" id="floating_standard"
                            @class([
                                'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2  appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                                'border-gray-300' => !$errors->has('email'),
                                'border-red-600 focus:border-red-600' => $errors->has('email'),
                            ]) placeholder=" " />
                        <label for="floating_standard"
                            @class([
                                'absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0  peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto',
                                'peer-focus:text-blue-600' => !$errors->has('email'),
                                'peer-focus:text-red-600' => $errors->has('email'),
                                'text-red-600' => $errors->has('email'),
                            ])>{{ __('frontend.form.register-form.your-email') }}</label>
                        @error('email')
                            <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}</p>
                        @enderror
                    </div>

                    <button wire:click.prevent='resetPassword' type="submit"
                        class="w-full text-white bg-teal-500 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">{{ __('frontend.form.forgot-password-form.reset') }}</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        <a href="{{ route('login') }}"
                            class="font-medium text-primary-600 hover:underline dark:text-primary-500">{{ __('frontend.form.forgot-password-form.or-sign-in') }}</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
