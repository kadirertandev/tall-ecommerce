<section>
    <div
        class="min-h-screen flex flex-col items-center justify-center px-6 py-8 mx-auto my-4 {{-- my-16 --}} lg:py-0">
        <a href="/" class="mb-3 text-4xl main-red">
            <span class="font-extralight">eco</span><span class="font-extrabold">mmerce</span>
        </a>
        <div class="w-full bg-white border-2 border-gray-100 rounded-lg shadow-xl sm:max-w-md xl:p-0 dark:bg-gray-800">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 ">
                <h1 class="text-2xl font-thin leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    {{ __('frontend.form.login-form.forgot-password') }}
                </h1>
                <form class="space-y-4 md:space-y-6">
                    <div class="relative z-0">
                        <input wire:model.blur='email' type="text" id="floating_standard"
                            @class([
                                'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2  appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-hidden focus:ring-0 focus:border-blue-600 peer',
                                'border-gray-300!' => !$errors->has('email'),
                                'border-red-600! focus:border-red-600!' => $errors->has('email'),
                            ]) placeholder=" " />
                        <label for="floating_standard"
                            @class([
                                'absolute! text-sm! text-gray-500! duration-300! transform! -translate-y-6! scale-75! top-3! -z-10! origin-[0]! peer-focus:start-0!  peer-placeholder-shown:scale-100! peer-placeholder-shown:translate-y-0! peer-focus:scale-75! peer-focus:-translate-y-6! peer-focus:rtl:translate-x-1/4! peer-focus:rtl:left-auto!',
                                'peer-focus:text-blue-600!' => !$errors->has('email'),
                                'peer-focus:text-red-600!' => $errors->has('email'),
                                'text-red-600!' => $errors->has('email'),
                            ])>{{ __('frontend.form.register-form.your-email') }}</label>
                        @error('email')
                            <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}</p>
                        @enderror
                    </div>

                    <button wire:click.prevent='resetPassword' type="submit"
                        class="w-full text-white bg-teal-500 hover:bg-primary-700 focus:ring-2 focus:outline-hidden focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">{{ __('frontend.form.forgot-password-form.reset') }}</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        <a href="{{ route('login') }}"
                            class="font-medium text-primary-600 hover:underline dark:text-primary-500">{{ __('frontend.form.forgot-password-form.or-sign-in') }}</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
