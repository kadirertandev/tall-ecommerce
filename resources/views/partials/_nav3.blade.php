<div class="py-2 bg-main-red">
    <div class="main-container">
        <h1 class="text-2xl font-bold text-white font-roboto">
            <button id="categories" data-dropdown-toggle="categoriesDropdown" data-dropdown-trigger="hover"
                class="inline-flex items-center gap-1">
                {{ __('frontend.all-categories') }}<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="currentColor" class="w-8 h-8">
                    <path fill-rule="evenodd"
                        d="M3 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 5.25Zm0 4.5A.75.75 0 0 1 3.75 9h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 9.75Zm0 4.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Zm0 4.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <div id="categoriesDropdown"
                class="z-40 hidden bg-white border-2 divide-y divide-gray-100 rounded-lg shadow-lg border-zinc-100 min-w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 *:text-nowrap min-w-max"
                    aria-labelledby="dropdownHoverButton">
                    @foreach ($categories as $category)
                        <li>
                            <a href="{{ route('category-slug', [
                                'slug' => $category->slug,
                            ]) }}"
                                class="block px-4 py-2 hover:text-main-red! hover:px-5!">
                                {{ __('categories.' . $category->slug . '.name') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

        </h1>
    </div>
</div>
