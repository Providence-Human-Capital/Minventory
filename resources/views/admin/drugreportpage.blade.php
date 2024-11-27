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
                            Stock distributed throughout {{now()->year}} 
                        </div>
                        <div class="card-body">
                            <!-- Table Area -->
                            <div id="printArea" class="print-area">
                                
                            </div>
                            <!-- Chart Area -->
                            <canvas id="resultChart" style="margin-top: 20px;"></canvas>
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
        $(document).ready(function() {
            fetchDrugReport();

            function fetchDrugReport() {
                $.ajax({
                    url: '{{ route('getdrugreport') }}',
                    method: 'GET',
                    success: function(data) {
                        $('#resultTableBody').html(data.html);
                        renderChart(data.chartData);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        alert('An error occurred while fetching data.');
                    }
                });
            }

            function renderChart(chartData) {
                const ctx = document.getElementById('resultChart').getContext('2d');

                if (window.myChart) {
                    window.myChart.destroy();
                }

                window.myChart = new Chart(ctx, {
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
                            y: { beginAtZero: true },
                        },
                    },
                });
            }

            window.printResults = function() {
                const printContents = document.getElementById('printArea').innerHTML;
                const originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            };

            window.fetchGraphData = function() {
                const month = $('#monthSelect').val();
                const year = $('#yearSelect').val();

                alert(`Fetching graph data for ${month}, ${year}.`);
                // Add AJAX request here to fetch and update graph data based on month/year
            };
        });
    </script>
</x-app-layout>
