<div>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-screen-2xl">
            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div class="flex flex-row items-end justify-between px-4 lg:space-y-0 lg:space-x-4">
                    <div class="flex-1">
                        <h1 class="text-gray-500">Dashboard</h1>
                        <div class="max-w-[500px] max-h-96 flex flex-col">
                            <h2 class="flex items-center self-center gap-2">
                                <span>Sales & Revenue - Last </span>
                                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                                    class="inline-flex items-center px-2 text-sm font-medium text-center text-black rounded-lg ring-2 ring-gray-400 focus:outline-none"
                                    type="button">
                                    <span>{{ $months }}</span>
                                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <span>Months</span>
                            </h2>
                            <!-- Dropdown menu -->
                            <div id="dropdown"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownDefaultButton">
                                    <li @click="$wire.set('months',6)"
                                        class="block px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">
                                        6</li>
                                    <li @click="$wire.set('months',12)"
                                        class="block px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">
                                        12</li>
                                </ul>
                            </div>

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
            let monthlySalesBaseData = $wire.monthlySales;
            let monthlySalesLabels = monthlySalesBaseData.map(item => item.month);
            let monthlySalesData = monthlySalesBaseData.map(item => item.data);
            console.log(monthlySalesBaseData);

            let monthlyRevenueBaseData = $wire.monthlyRevenue;
            let monthlyRevenueLabels = monthlyRevenueBaseData.map(item => item.month);
            let monthlyRevenueData = monthlyRevenueBaseData.map(item => item.data);
            console.log(monthlySalesBaseData);

            window.chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlySalesLabels.sort((a, b) => a.order - b.order).reverse(),
                    datasets: [{
                        label: 'Monthly Sales',
                        data: monthlySalesData.reverse(),
                        borderWidth: 3
                    }, {
                        label: 'Monthly Revenue',
                        data: monthlyRevenueData.reverse(),
                        borderWidth: 3
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        createChart();

        Livewire.on("months-updated", function() {
            window.chart.destroy();
            createChart();
        });
    </script>
@endscript
