<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <x-nav-link :href="route('showDrugReport')" :active="request()->routeIs('showDrugReport')">
                        {{ __('Drugs') }}
                    </x-nav-link>
                    <x-nav-link :href="route('batch')" :active="request()->routeIs('batch')">
                        {{ __('ClinicStock') }}
                    </x-nav-link>
                </div>
                <div class="col-sm"></div>
                <div class="col-sm text-end">
                    <form action="{{ route('searchmainstock') }}" method="GET" class="d-flex">
                        <input type="text" name="isearch" id="isearch" class="form-control me-2" value="{{ old('isearch') }}" placeholder="Search">
                        <button type="submit" class="btn btn-outline-primary">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <center>
            <div class="alert-container" style="width:80%; margin-top:30px;">
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

        <div class="row mt-4">
            <!-- Left Panel -->
            <div class="col-md-3">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                    <form id="clinicForm" method="POST" action="{{route('batchchart')}}">
                        @csrf
                        <fieldset>
                            <legend class="fw-bold">Clinic Selection</legend>
                            <div class="mb-3">
                                <label for="clinics" class="form-label">Select Clinic:</label>
                                <select name="clinics" id="clinics" class="form-select">
                                    <option value="" disabled selected>Select a clinic</option>
                                    @foreach (DB::table('clinics')->get('clinic_name') as $clinic)
                                        <option value="{{ $clinic->clinic_name }}">{{ $clinic->clinic_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="month" class="form-label">Select Month:</label>
                                <select id="month" name="month" class="form-select">
                                    <option value="" disabled selected>Select a month</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="year" class="form-label">Select Year:</label>
                                <select id="year" name="year" class="form-select">
                                    <option value="" disabled selected>Select a year</option>
                                    @for ($year = date('Y'); $year >= 2000; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Submit</button>
                        </fieldset>
                    </form>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="col-md-9">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                    <button class="btn btn-primary mb-3" onclick="printResults()">
                        <i class="fa fa-print"></i> Print Results
                    </button>
                    <div id="printArea" class="print-area">
                        <div id="resultContainer">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Quantity Distributed</th>
                                        <th>Current Clinic Stock</th>
                                        <th>Total Used</th>
                                    </tr>
                                </thead>
                                <tbody id="resultTableBody">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <canvas id="resultChart" class="mt-4"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            $('#clinicForm').on('submit', function(event) {
                event.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('batchchart') }}',
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        $('#resultTableBody').html(data.html);
                        updateChart(data.chartData);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        alert('An error occurred while fetching data.');
                    }
                });
            });
        });

        function updateChart(chartData) {
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
                        borderWidth: 1
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

        function printResults() {
            const printContents = document.getElementById('printArea').innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</x-app-layout>
