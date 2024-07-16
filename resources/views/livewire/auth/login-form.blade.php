<section>
    <div
        class="min-h-screen flex flex-col items-center justify-center px-6 py-8 mx-auto my-4 {{-- my-16 --}} lg:py-0">
        <a href="/" class="text-4xl main-red mb-3">
            <span class="font-extralight">eco</span><span class="font-extrabold">mmerce</span>
        </a>
        <div class="w-full bg-white rounded-lg shadow-xl sm:max-w-md xl:p-0 dark:bg-gray-800 border-2 border-gray-100">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 ">
                <h1 class="text-2xl font-thin leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    {{ __('frontend.form.login-form.sign-in-to-your-account') }}
                </h1>
                <form class="space-y-4 md:space-y-6">
                    {{-- <div>
                        <label for="email"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('frontend.form.login-form.your-email') }}</label>
                        <input wire:model.blur='form.email' type="email" name="email" id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="name@company.com" required="">
                        @error('form.email')
                            <p class="flex items-center gap-1 p-2 bg-red-500 text-white mt-1 rounded-lg text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label for="password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('frontend.form.login-form.password') }}</label>
                        <input wire:model='form.password' type="password" name="password" id="password"
                            placeholder="••••••••"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required="">
                        @error('form.password')
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
                        <input wire:model.blur='form.email' type="text" id="floating_standard"
                            @class([
                                'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2  appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                                'border-gray-300' => !$errors->has('form.email'),
                                'border-red-600 focus:border-red-600' => $errors->has('form.email'),
                            ]) placeholder=" " />
                        <label for="floating_standard"
                            @class([
                                'absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0  peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto',
                                'peer-focus:text-blue-600' => !$errors->has('form.email'),
                                'peer-focus:text-red-600' => $errors->has('form.email'),
                                'text-red-600' => $errors->has('form.email'),
                            ])>{{ __('frontend.form.register-form.email') }}</label>
                        @error('form.email')
                            <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative z-0">
                        <input wire:model.blur='form.password' type="password" name="password" id="password"
                            @class([
                                'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2  appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                                'border-gray-300' => !$errors->has('form.password'),
                                'border-red-600 focus:border-red-600' => $errors->has('form.password'),
                            ]) placeholder=" " />
                        <label for="floating_standard"
                            @class([
                                'absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0  peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto',
                                'peer-focus:text-blue-600' => !$errors->has('form.password'),
                                'peer-focus:text-red-600' => $errors->has('form.password'),
                                'text-red-600' => $errors->has('form.password'),
                            ])>{{ __('frontend.form.register-form.password') }}</label>
                        @error('form.password')
                            <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input wire:model='form.remember_me' id="remember" aria-describedby="remember"
                                    type="checkbox"
                                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800"
                                    required="">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="remember"
                                    class="text-gray-500 dark:text-gray-300">{{ __('frontend.form.login-form.remember-me') }}</label>
                            </div>
                        </div>
                        <a href="{{ route('forgot-password') }}"
                            class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">{{ __('frontend.form.login-form.forgot-password') }}</a>
                    </div>
                    <button wire:click.prevent='login' type="submit"
                        class="w-full text-white bg-teal-500 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">{{ __('frontend.form.login-form.sign-in') }}</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        {{ __('frontend.form.login-form.dont-have-an-account-yet') }} <a href="{{ route('register') }}"
                            class="font-medium text-primary-600 hover:underline dark:text-primary-500">{{ __('frontend.form.login-form.sign-up') }}</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
