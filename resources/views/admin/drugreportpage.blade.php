<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <x-nav-link :href="route('showDrugReport')" :active="request()->routeIs('showDrugReport')">
                        {{ __('Drugs') }}
                    </x-nav-link>
                    <x-nav-link :href="route('batch')" :active="request()->routeIs('batch')">
                        {{ __('Batch Clinic') }}
                    </x-nav-link>
                </div>
            </div>
        </div>
    </x-slot>

    <center>
        <div style="width:80%;margin-top:30px">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif
        </div>
    </center>

    <div class="py-12">
        <div class="container">
            <div class="row">
                <!-- Sidebar Column -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h5>Report Options</h5>
                        </div>
                        <div class="card-body">
                            <p>Yearly</p>
                            <p>Month</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content Column -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            Stock distributed throughout {{ now()->year }}
                        </div>
                        <div class="card-body">
                            <!-- Chart Area -->
                            <canvas id="resultChart" style="margin: 20px;"></canvas>
                        </div>
                    </div>
                
                <div class="card">
                    <div class="card-header">
                        Stock dispensed throughout {{ now()->year }}
                    </div>
                    <div class="card-body">
                        <!-- Chart Area -->
                        <canvas id="resultChart2" style="margin-top: 20px;"></canvas>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>



    <!-- Include Chart.js and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let chart1, chart2;
        $(document).ready(function() {
            fetchDrugReport();

            function fetchDrugReport() {
                $.ajax({
                    url: '{{ route('getdrugreport') }}',
                    method: 'GET',
                    success: function(data) {
                        console.log('Fetched Data:',
                            data); // Log the data to ensure it's correctly received

                        // Ensure data is defined before proceeding
                        if (data && data.chartData && data.chartData2) {
                            // Populate Table


                            // Render Charts
                            renderChart(data.chartData, 'resultChart', chart1);
                            renderChart(data.chartData2, 'resultChart2', chart2);
                        } else {
                            console.error('Invalid data format:', data);
                            alert('No valid data available to display.');
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Request Failed:', xhr);
                        alert('An error occurred while fetching the drug report.');
                    }
                });
            }


            function renderChart(chartData, chartId, chartInstance) {
                const ctx = document.getElementById(chartId).getContext('2d');

                if (chartInstance) {
                    chartInstance.destroy();
                }

                chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Quantity Distributed',
                            data: chartData.values,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                        }],
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                        },
                    },
                });
            }
        });
    </script>
</x-app-layout>
