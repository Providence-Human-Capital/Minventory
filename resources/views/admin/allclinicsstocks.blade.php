<x-app-layout>
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
                                            <option value="" disabled selected>Select a clinic</option>
                                            <option value="81 Baines Avenue(Harare)">81 Baines Avenue (Harare)</option>
                                            <option value="52 Baines Avenue(Harare)">52 Baines Avenue (Harare)</option>
                                            <option value="64 Cork Road Avondale(Harare)">64 Cork Road Avondale (Harare)
                                            </option>
                                            <option value="40 Josiah Chinamano Avenue(Harare)">40 Josiah Chinamano
                                                Avenue (Harare)</option>
                                            <option value="Epworth Clinic(Harare)">Epworth Clinic (Harare)</option>
                                            <option value="Fort Street and 9th Avenue(Bulawayo)">Fort Street and 9th
                                                Avenue (Bulawayo)</option>
                                            <option value="Royal Arcade Complex(Bulawayo)">Royal Arcade Complex
                                                (Bulawayo)</option>
                                            <option value="39 6th Street(GWERU)">39 6th Street (Gweru)</option>
                                            <option value="126 Herbert Chitepo Street(Mutare)">126 Herbert Chitepo
                                                Street (Mutare)</option>
                                            <option value="13 Shuvai Mahofa Street(Masvingo)">13 Shuvai Mahofa Street
                                                (Masvingo)</option>
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
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div id="resultContainer" style="margin-top: 20px;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Quantity Distributed</th>
                                            <th>Current Clinic Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resultTableBody">
                                        <!-- Dynamic rows will be appended here -->
                                    </tbody>
                                </table>
                            </div>
                            <canvas id="resultChart" style="margin-top: 20px;"></canvas>
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
                    url: '{{ route('showclinicchart') }}', // Your route to fetch data
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        console.log(data);
                        $('#resultTableBody').html(data.html); // Update HTML with returned data
                        updateChart(data.chartData); // Update chart with returned data
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
    
    // Destroy existing chart if it exists
    if (window.myChart) {
        window.myChart.destroy();
    }

    // Create a new chart instance and assign it to window.myChart
    window.myChart = new Chart(ctx, {
        type: 'bar', // Change to desired chart type
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Drugs distributed',
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

    </script>

</x-app-layout>
