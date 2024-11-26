<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <x-nav-link :href="route('getdrugreport')" :active="request()->routeIs('getdrugreport')">
                        {{ __('Drugs') }}
                    </x-nav-link>
                    <x-nav-link :href="route('getallstocks')" :active="request()->routeIs('getallstocks')">
                        {{ __('Clinic') }}
                    </x-nav-link>
                    <x-nav-link :href="route('batch')" :active="request()->routeIs('batch')">
                        {{ __('batchClinic') }}
                    </x-nav-link>
                </div>
                <div class="col-sm">
                </div>
                <div class="col-sm">
                    <form action="{{ route('searchmainstock') }}" method="GET">
                        <input type="text" name="isearch" id="isearch" value="{{ old('isearch') }}">
                        <button type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>




    </x-slot>
    <div>
        <div class="max-w-7xl mx-auto sm:px-2 lg:px-1">



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
            <div class="container">
                <div class="row">
                    <div class="col-3 ">
                        <div class="py-12">

                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100">

                                    <style>
                                        body {
                                            font-family: Arial, sans-serif;
                                            padding: 20px;
                                        }

                                        .form-select {
                                            width: 100%;
                                            padding: 10px;
                                            margin-bottom: 20px;
                                        }

                                        .submit-button {
                                            background-color: #4CAF50;
                                            /* Green */
                                            color: white;
                                            padding: 10px 20px;
                                            border: none;
                                            border-radius: 5px;
                                            cursor: pointer;
                                        }

                                        .submit-button:hover {
                                            background-color: #45a049;
                                        }

                                        fieldset {
                                            border: 1px solid #ccc;
                                            padding: 20px;
                                            border-radius: 5px;
                                        }

                                        legend {
                                            font-weight: bold;
                                        }
                                    </style>
                                    </head>

                                    <body>

                                        <form id="clinicForm">
                                            @csrf
                                            <fieldset>
                                                <legend>Clinic Selection</legend>

                                                <label for="clinics">Select Clinic:</label>
                                                <select name="clinics" id="clinics" class="form-select">
                                                    <?php
                                                    $clinics = DB::table('clinics')->get('clinic_name');
                                                    ?>
                                                    <option value="" disabled selected>Select a clinic</option>
                                                    @foreach ($clinics as $clinic)
                                                        <option value="{{ $clinic->clinic_name }}">
                                                            {{ $clinic->clinic_name }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                                <label for="month">Select Month:</label>
                                                <select id="month" name="month" class="form-select">
                                                    <option value="" disabled selected>Select a month</option>
                                                    <option value="1">January</option>
                                                    <option value="2">February</option>
                                                    <option value="3">March</option>
                                                    <option value="4">April</option>
                                                    <option value="5">May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>

                                                <label for="year">Select Year:</label>
                                                <select id="year" name="year" class="form-select">
                                                    <option value="" disabled selected>Select a year</option>
                                                    <script>
                                                        const startYear = 2000;
                                                        const endYear = new Date().getFullYear();
                                                        for (let year = endYear; year >= startYear; year--) {
                                                            document.write(`<option value="${year}">${year}</option>`);
                                                        }
                                                    </script>
                                                </select>

                                                <button type="submit" class="submit-button">Submit</button>
                                            </fieldset>
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="py-12">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <button class="btn btn-primary mb-3" onclick="printResults()">
                                    <i class="fa fa-print"></i> Print Results
                                </button>
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    <div id="printArea" class="print-area">
                                        <div id="resultContainer" style="margin-top: 20px;">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Item Name</th>
                                                        <th>Quantity Distributed</th>
                                                        <th>Current Clinic Stock</th>
                                                        <th>Total used</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="resultTableBody">
                                                    <!-- Dynamic rows will be appended here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <canvas id="resultChart" style="margin-top: 20px;"></canvas>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            $('#clinicForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                const formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: '{{ route('showclinicchart') }}', // Your route to handle the request
                    method: 'POST',
                    data: formData, // Send form data to the server
                    success: function(data) {
                        console.log(data); // Log the returned data for debugging
                        $('#resultTableBody').html(data
                            .html); // Update the table body with HTML
                        updateChart(data.chartData); // Update the chart with the data
                    },
                    error: function(xhr) {
                        console.error(xhr); // Log the error response
                        alert('An error occurred while fetching data.');
                    }
                });
            });
        });

        // Function to update the chart
        function updateChart(chartData) {
            const ctx = document.getElementById('resultChart').getContext('2d');

            // Destroy existing chart if any
            if (window.myChart) {
                window.myChart.destroy();
            }

            // Create a new chart
            window.myChart = new Chart(ctx, {
                type: 'bar', // You can change the chart type here (e.g., 'line', 'bar')
                data: {
                    labels: chartData.labels, // Chart labels (e.g., item names)
                    datasets: [{
                        label: 'Quantity Distributed',
                        data: chartData.values, // Chart values (e.g., quantities distributed)
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Background color
                        borderColor: 'rgba(75, 192, 192, 1)', // Border color
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true // Start the y-axis from 0
                        }
                    }
                }
            });
        }

        // Print results function
        function printResults() {
            var printContents = document.getElementById('printArea').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>

</x-app-layout>
