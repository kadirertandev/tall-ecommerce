<div>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-screen-2xl">
            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div class="flex flex-row items-end justify-between px-4 lg:space-y-0 lg:space-x-4">
                    <div class="flex-1">
                        <div wire:ignore class="flex flex-wrap gap-4 mt-4 mb-8 place-content-center">
                            <div
                                class="flex flex-col items-center w-1/3 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20" viewBox="0 0 48 48">
                                    <g fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="4">
                                        <path d="M41 14L24 4L7 14v20l17 10l17-10z" />
                                        <path stroke-linecap="round" d="M24 22v8m8-12v12m-16-4v4" />
                                    </g>
                                </svg>
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Total
                                    Sales</h5>
                                <h5 class="mb-2 text-5xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    <strong>{{ $totalSales }}</strong>
                                </h5>
                            </div>
                            <div
                                class="flex flex-col items-center flex-1 w-1/3 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M12 12.5a3.5 3.5 0 1 0 0 7a3.5 3.5 0 0 0 0-7M10.5 16a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0" />
                                    <path fill="currentColor"
                                        d="M17.526 5.116L14.347.659L2.658 9.997L2.01 9.99V10H1.5v12h21V10h-.962l-1.914-5.599zM19.425 10H9.397l7.469-2.546l1.522-.487zM15.55 5.79L7.84 8.418l6.106-4.878zM3.5 18.169v-4.34A3 3 0 0 0 5.33 12h13.34a3 3 0 0 0 1.83 1.83v4.34A3 3 0 0 0 18.67 20H5.332A3.01 3.01 0 0 0 3.5 18.169" />
                                </svg>
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Total
                                    Revenue</h5>
                                <h5
                                    class="mb-2 text-5xl font-bold tracking-tight text-gray-900 dark:text-white text-nowrap">
                                    <strong>{{ \App\Helpers::formatPrice($totalRevenue) }} <span>TL</span></strong>
                                </h5>
                            </div>
                            <div
                                class="flex flex-col items-center w-1/3 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20" viewBox="0 0 32 32">
                                    <path fill="currentColor"
                                        d="M0 6v2h19v15h-6.156c-.446-1.719-1.992-3-3.844-3c-1.852 0-3.398 1.281-3.844 3H4v-5H2v7h3.156c.446 1.719 1.992 3 3.844 3c1.852 0 3.398-1.281 3.844-3h8.312c.446 1.719 1.992 3 3.844 3c1.852 0 3.398-1.281 3.844-3H32v-8.156l-.063-.157l-2-6L29.72 10H21V6zm1 4v2h9v-2zm20 2h7.281L30 17.125V23h-1.156c-.446-1.719-1.992-3-3.844-3c-1.852 0-3.398 1.281-3.844 3H21zM2 14v2h6v-2zm7 8c1.117 0 2 .883 2 2s-.883 2-2 2s-2-.883-2-2s.883-2 2-2m16 0c1.117 0 2 .883 2 2s-.883 2-2 2s-2-.883-2-2s.883-2 2-2">
                                    </path>
                                </svg>
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Total
                                    Orders</h5>
                                <h5 class="mb-2 text-5xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    <strong>{{ $totalOrders }}</strong>
                                </h5>
                            </div>
                            <div
                                class="flex flex-col items-center w-1/3 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20" viewBox="0 0 32 32">
                                    <path fill="currentColor" d="M8 18h6v2H8zm0 4h10v2H8z"></path>
                                    <path fill="currentColor"
                                        d="M26 4H6a2.003 2.003 0 0 0-2 2v20a2.003 2.003 0 0 0 2 2h20a2.003 2.003 0 0 0 2-2V6a2.003 2.003 0 0 0-2-2m-8 2v4h-4V6ZM6 26V6h6v6h8V6h6l.001 20Z">
                                    </path>
                                </svg>
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Total
                                    Products</h5>
                                <h5 class="mb-2 text-5xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    <strong>{{ $totalProducts }}</strong>
                                </h5>
                            </div>
                            <div
                                class="flex flex-col items-center w-1/3 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20" viewBox="0 0 24 24">
                                    <circle cx="12" cy="6" r="4" fill="currentColor"></circle>
                                    <path fill="currentColor"
                                        d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5">
                                    </path>
                                </svg>
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Total
                                    Customers</h5>
                                <h5 class="mb-2 text-5xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    <strong>{{ $totalCustomers }}</strong>
                                </h5>
                            </div>

                        </div>
                        <div>
                            <h2 class="flex items-center justify-center gap-2">
                                <span>Sales & Revenue - </span>
                                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                                    class="inline-flex items-center text-sm font-medium text-center text-black rounded-lg focus:ring-1 focus:ring-gray-400 {{-- ring-2 ring-gray-400 focus:outline-none --}}"
                                    type="button">
                                    @if (str_contains($filter, '_'))
                                        <span>Last
                                            {{ explode('_', $filter)[1] . ' ' . ucfirst(explode('_', $filter)[2]) }}</span>
                                    @else
                                        <span>{{ ucfirst($filter) }}</span>
                                    @endif
                                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdown"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownDefaultButton">
                                        <li @click="$wire.set('filter','today')"
                                            class="block px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">
                                            Today</li>
                                        <li @click="$wire.set('filter','yesterday')"
                                            class="block px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">
                                            Yesterday</li>
                                        <li @click="$wire.set('filter','last_7_days')"
                                            class="block px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">
                                            Last 7 Days</li>
                                        <li @click="$wire.set('filter','last_30_days')"
                                            class="block px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">
                                            Last 30 Days</li>
                                        <li @click="$wire.set('filter','last_90_days')"
                                            class="block px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">
                                            Last 90 Days</li>
                                        <li @click="$wire.set('filter','last_6_months')"
                                            class="block px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">
                                            Last 6 Months</li>
                                        <li @click="$wire.set('filter','last_12_months')"
                                            class="block px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">
                                            Last 12 Months</li>
                                    </ul>
                                </div>
                            </h2>
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@assets
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script
    <script>
        const ctx = document.getElementById('myChart');

        function createChart() {
            let monthlySalesBaseData = $wire.salesData;
            let monthlySalesLabels = monthlySalesBaseData.data.map(item => item.label);
            let monthlySalesData = monthlySalesBaseData.data.map(item => item.data);
            console.log(monthlySalesBaseData);

            let monthlyRevenueBaseData = $wire.revenueData;
            let monthlyRevenueLabels = monthlyRevenueBaseData.data.map(item => item.label);
            let monthlyRevenueData = monthlyRevenueBaseData.data.map(item => item.data);
            console.log(monthlySalesBaseData);

            window.chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlySalesLabels.sort((a, b) => a.order - b.order).reverse(),
                    datasets: [{
                        label: monthlySalesBaseData.header_label,
                        data: monthlySalesData.reverse(),
                        borderWidth: 3
                    }, {
                        label: monthlyRevenueBaseData.header_label,
                        data: monthlyRevenueData.reverse(),
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        createChart();

        Livewire.on("filter-updated", function() {
            window.chart.destroy();
            createChart();
        });
    </script>
@endscript
