<x-app-layout>
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
            }

            .print-area {
                margin: 0;
                padding: 0;
                font-size: 12px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
        }
    </style>

    <x-slot name="header">
        <!-- Header -->
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <x-nav-link :href="route('showDrugReport')" :active="request()->routeIs('showDrugReport')">
                        {{ __('Global Stats') }}
                    </x-nav-link>
                    <x-nav-link :href="route('batch')" :active="request()->routeIs('batch')">
                        {{ __('Clinic Stats') }}
                    </x-nav-link>
                    <x-nav-link :href="route('valuereport')" :active="request()->routeIs('valuereport')">
                        {{ __('Value Stats') }}
                    </x-nav-link>
                </div>
                <div class="col-sm"></div>
                <div class="col-sm text-end">
                    <form action="{{ route('searchmainstock') }}" method="GET" class="d-flex">
                        <input type="text" name="isearch" id="isearch" class="form-control me-2"
                            value="{{ old('isearch') }}" placeholder="Search">
                        <button type="submit" class="btn btn-outline-primary">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="row mt-4">
            <!-- Left Panel -->
            <div class="col-md-3">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                    <form id="clinicForm" method="POST" action="{{ route('getvaluereport') }}">
                        @csrf
                        <fieldset>
                            <legend class="fw-bold">Global Value Stock</legend>
                            <!-- Form -->
                            <div class="mb-3">
                                <label for="mode" class="form-label">Select Report Mode:</label>
                                <select name="mode" id="mode" class="form-select">
                                    <option value="" disabled selected>Select a Mode</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="monthly">Monthly</option>
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
                            <div class="mb-3" id="monthContainer">
                                <label for="month" class="form-label">Select Month:</label>
                                <select id="month" name="month" class="form-select">
                                    <option selected>Select a month</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
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
                            <div id="clinicData">
                                <!-- Dynamic clinic tables -->
                            </div>
                        </div>
                    </div>
                    <canvas id="resultChart" class="mt-4"></canvas>
                </div>
            </div>
        </div>
    </div>

   <!-- <script>
        $(document).ready(function () {
            $('#clinicForm').on('submit', function (event) {
                event.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('getvaluereport') }}',
                    method: 'POST',
                    data: formData,
                    success: function (data) {
                        const clinicData = data.clinicData || [];
                        const $clinicContainer = $('#clinicData');
                        $clinicContainer.empty(); // Clear previous data
                        
                        clinicData.forEach(clinic => {
                            const clinicTable = `
                                <h5>${clinic.clinic_name}</h5>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Sent Quantity</th>
                                            <th>Sent Value($)</th>
                                            <th>Current Stock</th>
                                            <th>Current Value($)</th>
                                            <th>Used Quantity</th>
                                            <th>Used Value($)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${clinic.stocks.map(stock => `
                                            <tr>
                                                <td>${stock.item_name}</td>
                                                <td>${stock.sent_quantity}</td>
                                                <td>${stock.sent_quantity_value}</td>
                                                <td>${stock.current_stock}</td>
                                                <td>${stock.current_stock_value}</td>
                                                <td>${stock.used_quantity}</td>
                                                <td>${stock.used_quantity_value}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            `;
                            $clinicContainer.append(clinicTable);
                        });
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || 'Error fetching data.');
                    }
                });
            });
        });
    </script>
-->
</x-app-layout>
