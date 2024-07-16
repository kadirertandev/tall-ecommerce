<div class="min-h-screen">
    <div class="main-container py-4">
        <div class="w-full bg-white">
            <div>
                <main>
                    <section aria-labelledby="products-heading" class="pb-24 pt-6">
                        <h2 id="products-heading" class="sr-only">Products</h2>

                        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                            <!-- Filters -->
                            <form class="hidden lg:block">
                                <h3 class="sr-only">Categories</h3>
                                <ul role="list"
                                    class="space-y-4 border-b border-gray-200 pb-6 text-sm font-medium text-gray-900">
                                    <li>
                                        <a href="#">Totes</a>
                                    </li>
                                    <li>
                                        <a href="#">Backpacks</a>
                                    </li>
                                    <li>
                                        <a href="#">Travel Bags</a>
                                    </li>
                                    <li>
                                        <a href="#">Hip Bags</a>
                                    </li>
                                    <li>
                                        <a href="#">Laptop Sleeves</a>
                                    </li>
                                </ul>

                                <div class="border-b border-gray-200 py-6">
                                    <h3 class="-my-3 flow-root">
                                        <!-- Expand/collapse section button -->
                                        <button type="button"
                                            class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500"
                                            aria-controls="filter-section-0" aria-expanded="false">
                                            <span class="font-medium text-gray-900">Color</span>
                                            <span class="ml-6 flex items-center">
                                                <!-- Expand icon, show/hide based on section open state. -->
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                    aria-hidden="true">
                                                    <path
                                                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                </svg>
                                                <!-- Collapse icon, show/hide based on section open state. -->
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                    aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </h3>
                                    <!-- Filter section, show/hide based on section state. -->
                                    <div class="pt-6" id="filter-section-0">
                                        <div class="space-y-4">
                                            <div class="flex items-center">
                                                <input id="filter-color-0" name="color[]" value="white" type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-color-0"
                                                    class="ml-3 text-sm text-gray-600">White</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-color-1" name="color[]" value="beige" type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-color-1"
                                                    class="ml-3 text-sm text-gray-600">Beige</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-color-2" name="color[]" value="blue" type="checkbox"
                                                    checked
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-color-2"
                                                    class="ml-3 text-sm text-gray-600">Blue</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-color-3" name="color[]" value="brown" type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-color-3"
                                                    class="ml-3 text-sm text-gray-600">Brown</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-color-4" name="color[]" value="green" type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-color-4"
                                                    class="ml-3 text-sm text-gray-600">Green</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-color-5" name="color[]" value="purple" type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-color-5"
                                                    class="ml-3 text-sm text-gray-600">Purple</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-b border-gray-200 py-6">
                                    <h3 class="-my-3 flow-root">
                                        <!-- Expand/collapse section button -->
                                        <button type="button"
                                            class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500"
                                            aria-controls="filter-section-1" aria-expanded="false">
                                            <span class="font-medium text-gray-900">Category</span>
                                            <span class="ml-6 flex items-center">
                                                <!-- Expand icon, show/hide based on section open state. -->
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                    aria-hidden="true">
                                                    <path
                                                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                </svg>
                                                <!-- Collapse icon, show/hide based on section open state. -->
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                    aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </h3>
                                    <!-- Filter section, show/hide based on section state. -->
                                    <div class="pt-6" id="filter-section-1">
                                        <div class="space-y-4">
                                            <div class="flex items-center">
                                                <input id="filter-category-0" name="category[]" value="new-arrivals"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-category-0" class="ml-3 text-sm text-gray-600">New
                                                    Arrivals</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-category-1" name="category[]" value="sale"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-category-1"
                                                    class="ml-3 text-sm text-gray-600">Sale</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-category-2" name="category[]" value="travel"
                                                    type="checkbox" checked
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-category-2"
                                                    class="ml-3 text-sm text-gray-600">Travel</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-category-3" name="category[]" value="organization"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-category-3"
                                                    class="ml-3 text-sm text-gray-600">Organization</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-category-4" name="category[]" value="accessories"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-category-4"
                                                    class="ml-3 text-sm text-gray-600">Accessories</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-b border-gray-200 py-6">
                                    <h3 class="-my-3 flow-root">
                                        <!-- Expand/collapse section button -->
                                        <button type="button"
                                            class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500"
                                            aria-controls="filter-section-2" aria-expanded="false">
                                            <span class="font-medium text-gray-900">Size</span>
                                            <span class="ml-6 flex items-center">
                                                <!-- Expand icon, show/hide based on section open state. -->
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                    aria-hidden="true">
                                                    <path
                                                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                </svg>
                                                <!-- Collapse icon, show/hide based on section open state. -->
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                    aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </h3>
                                    <!-- Filter section, show/hide based on section state. -->
                                    <div class="pt-6" id="filter-section-2">
                                        <div class="space-y-4">
                                            <div class="flex items-center">
                                                <input id="filter-size-0" name="size[]" value="2l"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-size-0"
                                                    class="ml-3 text-sm text-gray-600">2L</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-size-1" name="size[]" value="6l"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-size-1"
                                                    class="ml-3 text-sm text-gray-600">6L</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-size-2" name="size[]" value="12l"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-size-2"
                                                    class="ml-3 text-sm text-gray-600">12L</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-size-3" name="size[]" value="18l"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-size-3"
                                                    class="ml-3 text-sm text-gray-600">18L</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-size-4" name="size[]" value="20l"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-size-4"
                                                    class="ml-3 text-sm text-gray-600">20L</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="filter-size-5" name="size[]" value="40l"
                                                    type="checkbox" checked
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <label for="filter-size-5"
                                                    class="ml-3 text-sm text-gray-600">40L</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Product grid -->
                            <div class="lg:col-span-3">
                                Hello there
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>

        <div class="w-full flex items-start justify-between">
            <div class="w-2/12 sticky top-0 p-2 ps-0">

            </div>
            <div class="w-10/12">
                <div class="flex items-center justify-between p-2">
                    <p id="products-count-info"></p>
                    <button id="dropdownHoverButton" data-dropdown-toggle="dropdownHover"
                        data-dropdown-trigger="hover"
                        class="text-black {{-- hover:text-red-500 --}} ring-1 ring-gray-400 focus:outline-none  hover:ring-red-500 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center   dark:focus:ring-blue-800"
                        type="button"><span id="sort-text">Sırala</span> <svg xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red"
                            class="w-5 h-5 ms-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdownHover"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                        <ul class="*:cursor-pointer py-2 text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby="dropdownHoverButton">
                            <li id="btnSortByEdf" class="asc">
                                <span
                                    class="block px-4 py-2 hover:bg-gray-100 hover:text-red-500 dark:hover:bg-gray-600 ">En
                                    düşük fiyat</span>
                            </li>
                            <li id="btnSortByEyf" class="desc">
                                <span
                                    class="block px-4 py-2 hover:bg-gray-100 hover:text-red-500 dark:hover:bg-gray-600 ">En
                                    yüksek fiyat</span>
                            </li>
                            <li>
                                <span
                                    class="block px-4 py-2 hover:bg-gray-100 hover:text-red-500 dark:hover:bg-gray-600 ">En
                                    çok beğenilenler</span>
                            </li>
                            <li>
                                <span
                                    class="block px-4 py-2 hover:bg-gray-100 hover:text-red-500 dark:hover:bg-gray-600 ">En
                                    yeniler</span>
                            </li>
                            <li>
                                <span
                                    class="block px-4 py-2 hover:bg-gray-100 text-nowrap hover:text-red-500 dark:hover:bg-gray-600 ">En
                                    çok değerlendirilenler</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
