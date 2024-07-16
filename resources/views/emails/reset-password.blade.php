<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-md text-center lg:py-16 lg:px-12">
        <a href="localhost::8000" class="text-4xl font-roboto main-red mx-auto mb-4">
            <span class="font-extralight">eco</span><span class="font-extrabold">mmerce</span>
        </a>
        <h1
            class="mb-4 text-4xl font-bold tracking-tight leading-none text-gray-900 lg:mb-6 md:text-5xl xl:text-6xl dark:text-white">
            <a href="{{ route('reset-password', ['token' => $token]) }}">Reset Password</a>
        </h1>
        <p>
            Token: {{ $token }}
        </p>
    </div>
</section>
