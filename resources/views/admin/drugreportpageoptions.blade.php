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
                    <form id="clinicForm" method="POST" action="{{route('batchchart')}}">
                        @csrf
                        <fieldset>
                            <legend class="fw-bold">Clinic Selection</legend>
                            <div class="mb-3">
                                <label for="item_number" class="form-label">Select Drug:</label>
                                <select name="item_number" id="item_number" class="form-select">
                                    <option value="" disabled selected>Select a drug</option>
                                    @foreach (DB::table('stock_items')->get() as $drug)
                                        <option value="{{ $drug->item_number }}">{{ $drug->item_name }}</option>
                                    @endforeach
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
        $(document).ready(function() {
            $('#clinicForm').on('submit', function(event) {
                event.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('yeardrug') }}',
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        $('#resultTableBody').html(data.html);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        alert('An error occurred while fetching data.');
                    }
                });
            });
        });


        function printResults() {
            const printContents = document.getElementById('printArea').innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</x-app-layout>
