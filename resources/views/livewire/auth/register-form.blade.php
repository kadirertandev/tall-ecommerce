<section>
    <div
        class="min-h-screen flex flex-col items-center justify-center px-6 py-8 mx-auto my-4 {{-- my-16 --}} lg:py-0">
        <a href="/" class="mb-6 text-4xl main-red">
            <span class="font-extralight">eco</span><span class="font-extrabold">mmerce</span>
        </a>
        <div class="w-full bg-white rounded-lg shadow-xl sm:max-w-md xl:p-0 dark:bg-gray-800">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-2xl font-thin leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    {{ __('frontend.form.register-form.create-an-account') }}</h1>
                <form class="space-y-4 md:space-y-6" novalidate>
                    <div class="relative z-0">
                        <input wire:model.blur='form.first_name' type="text" id="floating_standard"
                            @class([
                                'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2  appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-hidden focus:ring-0 focus:border-blue-600 peer',
                                'border-gray-300!' => !$errors->has('form.first_name'),
                                'border-red-600! focus:border-red-600!' => $errors->has('form.first_name'),
                            ]) placeholder=" " />
                        <label for="floating_standard"
                            @class([
                                'absolute! text-sm! text-gray-500! duration-300! transform! -translate-y-6! scale-75! top-3! -z-10! origin-[0]! peer-focus:start-0!  peer-placeholder-shown:scale-100! peer-placeholder-shown:translate-y-0! peer-focus:scale-75! peer-focus:-translate-y-6! peer-focus:rtl:translate-x-1/4! peer-focus:rtl:left-auto!',
                                'peer-focus:text-blue-600!' => !$errors->has('form.first_name'),
                                'peer-focus:text-red-600!' => $errors->has('form.first_name'),
                                'text-red-600!' => $errors->has('form.first_name'),
                            ])>{{ __('frontend.form.register-form.first-name') }}</label>
                        @error('form.first_name')
                            <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative z-0">
                        <input wire:model.blur='form.last_name' type="text" id="floating_standard"
                            @class([
                                'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2  appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-hidden focus:ring-0 focus:border-blue-600 peer',
                                'border-gray-300!' => !$errors->has('form.last_name'),
                                'border-red-600! focus:border-red-600!' => $errors->has('form.last_name'),
                            ]) placeholder=" " />
                        <label for="floating_standard"
                            @class([
                                'absolute! text-sm! text-gray-500! duration-300! transform! -translate-y-6! scale-75! top-3! -z-10! origin-[0]! peer-focus:start-0!  peer-placeholder-shown:scale-100! peer-placeholder-shown:translate-y-0! peer-focus:scale-75! peer-focus:-translate-y-6! peer-focus:rtl:translate-x-1/4! peer-focus:rtl:left-auto!',
                                'peer-focus:text-blue-600!' => !$errors->has('form.last_name'),
                                'peer-focus:text-red-600!' => $errors->has('form.last_name'),
                                'text-red-600!' => $errors->has('form.last_name'),
                            ])>{{ __('frontend.form.register-form.last-name') }}</label>
                        @error('form.last_name')
                            <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative z-0">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex-1">
                                <input wire:model.blur='form.email' type="text" id="floating_standard"
                                    @class([
                                        'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2  appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-hidden focus:ring-0 focus:border-blue-600 peer',
                                        'border-gray-300!' => !$errors->has('form.email'),
                                        'border-red-600! focus:border-red-600!' => $errors->has('form.email'),
                                    ]) placeholder=" " />
                                <label for="floating_standard"
                                    @class([
                                        'absolute! text-sm! text-gray-500! duration-300! transform! -translate-y-6! scale-75! top-3! -z-10! origin-[0]! peer-focus:start-0!  peer-placeholder-shown:scale-100! peer-placeholder-shown:translate-y-0! peer-focus:scale-75! peer-focus:-translate-y-6! peer-focus:rtl:translate-x-1/4! peer-focus:rtl:left-auto!',
                                        'peer-focus:text-blue-600!' => !$errors->has('form.email'),
                                        'peer-focus:text-red-600!' => $errors->has('form.email'),
                                        'text-red-600!' => $errors->has('form.email'),
                                    ])>{{ __('frontend.form.register-form.email') }}</label>
                            </div>
                            <div x-data="{ ...$store.countdown }" x-on:start-countdown.window="start">
                                <button id="verification-button" wire:click='sendVerificationCode'
                                    @disabled($verificationCodeSent || !$canSendEmail) type="button" @class([
                                        'focus:outline-none bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5',
                                        'hover:bg-green-800' => !$verificationCodeSent && $canSendEmail,
                                        'text-white' => !$verificationCodeSent && $canSendEmail,
                                        'text-gray-300' => $verificationCodeSent || !$canSendEmail,
                                    ])
                                    x-text="isStarted ? seconds : 'Verification'"></button>
                            </div>
                        </div>
                        @error('form.email')
                            <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative z-0">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex-1">
                                <input @disabled(!$verificationCodeSent || $emailVerified) wire:model='emailVerificationCode' type="text"
                                    id="floating_standard" @class([
                                        'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2  appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-hidden focus:ring-0 focus:border-blue-600 peer',
                                        'border-gray-300!' => !$errors->has('emailVerificationCode'),
                                        'border-red-600! focus:border-red-600!' => $errors->has(
                                            'emailVerificationCode'),
                                    ]) placeholder=" " />
                                <label for="floating_standard" @class([
                                    'absolute! text-sm! text-gray-500! duration-300! transform! -translate-y-6! scale-75! top-3! -z-10! origin-[0]! peer-focus:start-0!  peer-placeholder-shown:scale-100! peer-placeholder-shown:translate-y-0! peer-focus:scale-75! peer-focus:-translate-y-6! peer-focus:rtl:translate-x-1/4! peer-focus:rtl:left-auto!',
                                    'peer-focus:text-blue-600!' => !$errors->has('emailVerificationCode'),
                                    'peer-focus:text-red-600!' => $errors->has('emailVerificationCode'),
                                    'text-red-600!' => $errors->has('emailVerificationCode'),
                                ])>Email verification
                                    code</label>
                            </div>
                            <div>
                                <button wire:click='check' @disabled(!$verificationCodeSent || $emailVerified) @class([
                                    'focus:outline-none bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5',
                                    'hover:bg-green-800' => $verificationCodeSent && !$emailVerified,
                                    'text-white' => $verificationCodeSent && !$emailVerified,
                                    'text-gray-300' => !$verificationCodeSent || $emailVerified,
                                ])
                                    type="button">Check</button>
                            </div>
                        </div>
                        @error('emailVerificationCode')
                            <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative z-0">
                        <input wire:model.blur='form.password' type="password" name="password" id="password"
                            @class([
                                'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2  appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-hidden focus:ring-0 focus:border-blue-600 peer',
                                'border-gray-300!' => !$errors->has('form.password'),
                                'border-red-600! focus:border-red-600!' => $errors->has('form.password'),
                            ]) placeholder=" " />
                        <label for="floating_standard"
                            @class([
                                'absolute! text-sm! text-gray-500! duration-300! transform! -translate-y-6! scale-75! top-3! -z-10! origin-[0]! peer-focus:start-0!  peer-placeholder-shown:scale-100! peer-placeholder-shown:translate-y-0! peer-focus:scale-75! peer-focus:-translate-y-6! peer-focus:rtl:translate-x-1/4! peer-focus:rtl:left-auto!',
                                'peer-focus:text-blue-600!' => !$errors->has('form.password'),
                                'peer-focus:text-red-600!' => $errors->has('form.password'),
                                'text-red-600!' => $errors->has('form.password'),
                            ])>{{ __('frontend.form.register-form.password') }}</label>
                        @error('form.password')
                            <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative z-0">
                        <input wire:model.blur='form.password_confirmation' type="password" name="password_confirmation"
                            id="password_confirmation" @class([
                                'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2  appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-hidden focus:ring-0 focus:border-blue-600 peer',
                                'border-gray-300!' => !$errors->has('form.password_confirmation'),
                                'border-red-600! focus:border-red-600!' => $errors->has(
                                    'form.password_confirmation'),
                            ]) placeholder=" " />
                        <label for="floating_standard"
                            @class([
                                'absolute! text-sm! text-gray-500! duration-300! transform! -translate-y-6! scale-75! top-3! -z-10! origin-[0]! peer-focus:start-0!  peer-placeholder-shown:scale-100! peer-placeholder-shown:translate-y-0! peer-focus:scale-75! peer-focus:-translate-y-6! peer-focus:rtl:translate-x-1/4! peer-focus:rtl:left-auto!',
                                'peer-focus:text-blue-600!' => !$errors->has('form.password_confirmation'),
                                'peer-focus:text-red-600!' => $errors->has('form.password_confirmation'),
                                'text-red-600!' => $errors->has('form.password_confirmation'),
                            ])>{{ __('frontend.form.register-form.password-confirm') }}</label>
                    </div>

                    <button wire:click.prevent='register' type="submit"
                        @class([
                            'w-full text-white bg-teal-500 hover:bg-primary-700 focus:ring-2 focus:outline-hidden focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800',
                            'animate-bounce' => $emailVerified,
                        ])>{{ __('frontend.form.register-form.create-an-account') }}</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        {{ __('frontend.form.register-form.already-have-an-account') }} <a href="{{ route('login') }}"
                            class="font-medium text-primary-600 hover:underline dark:text-primary-500">{{ __('frontend.form.register-form.login-here') }}</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>


@script
    <script>
        Alpine.store('countdown', {
            seconds: 120,
            isStarted: false,
            start() {
                this.isStarted = true
                let intervalID = setInterval(() => {
                    console.log(this.seconds--)

                    if (this.seconds == 0) {
                        this.stop(intervalID)
                    }
                }, 1000);
            },
            stop(id) {
                this.isStarted = false
                clearInterval(id)
                this.seconds = 120
                Livewire.dispatch("countdown-over")
            }
        })
    </script>
@endscript
