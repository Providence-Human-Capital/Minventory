<x-app-layout>
    <x-slot name="header">
        <div class="container">
            {{-- add modal button --}}
            <div class="py-1" style="float:right;">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>
                    </div>
                @endif
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

                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchModal">
                    Transaction Search
                </button>
            </div>
        </div>
        {{-- modal design --}}
        <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: green; color: white;">
                        <h5 class="modal-title" id="searchModalLabel">SEARCH</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/StockTransactions/search">
                        <div class="modal-body">
                            @csrf
                            <div class="container">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="item_name">Item Name</label>
                                        <input type="text" id="item_name" name="item_name" placeholder="Item Name"
                                            class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="item_number">Item Number</label>
                                        <input type="text" id="item_number" name="item_number"
                                            placeholder="Item Number" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="clinics">Choose a Clinic</label>
                                        <select name="clinics" id="clinics" class="form-control">
                                            <?php
                                            $clinics = DB::table('clinics')->get('clinic_name');
                                            ?>
                                            <?php
                                            $clinics = DB::table('clinics')->get('clinic_name');
                                            ?>

                                            <option value="" disabled selected>Select a clinic</option>
                                            @foreach ($clinics as $clinic)
                                                <option value="{{ $clinic->clinic_name }}">{{ $clinic->clinic_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('clinics')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="procurer">Procurer</label>
                                        <input type="text" id="procurer" name="procurer" placeholder="Procurer"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="transaction_date_from">Transaction Date</label>
                                        <div class="d-flex justify-content-between">
                                            <input type="date" id="transaction_date_from"
                                                name="transaction_date_from" class="form-control">
                                            <span class="mx-2 align-self-center">-</span>
                                            <input type="date" id="transaction_date_to" name="transaction_date_to"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="py-12">
                        <!-- Button to print results -->
                        <button class="btn btn-primary mb-3" onclick="printResults()">
                            <i class="fa fa-print"></i> Print Results
                        </button>

                        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-3 text-gray-900 dark:text-gray-100">
                                    <center>
                                        @if ($results->isEmpty())
                                            <p>No results found.</p>
                                        @else
                                            <div id="printArea" class="print-area">
                                                <table class="table table-striped table-bordered w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Item Name</th>
                                                            <th>Item Number</th>
                                                            <th>Quantity</th>
                                                            <th>Clinic</th>
                                                            <th>Date of transaction</th>
                                                            <th>Expiry Date</th>
                                                            <th>Procurer</th>
                                                            <th>Received By</th>
                                                            <th>Proof of Delivery</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($results as $result)
                                                            <tr>
                                                                <td>{{ $result->item_name }}</td>
                                                                <td>{{ $result->item_number }}</td>
                                                                <td>{{ $result->item_quantity }}</td>
                                                                <td>{{ $result->clinics }}</td>
                                                                <td>{{ $result->updated_at }}</td>
                                                                <td>{{ $result->expiry_date}}</td>
                                                                <td>{{ $result->procurer }}</td>
                                                                <td>{{ $result->recieved_by }}</td>

                                                                <th
                                                                    style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                                                    <button type="button" class="btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#viewModal{{ $result->id }}">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </th>
                                                                <th
                                                                    style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                                                    <button type="button" class="btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#viewrModal{{ $result->id }}">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </th>
                                                                {{-- modal design view --}}
                                                                <div class="modal fade mt-11 "
                                                                    id="viewModal{{ $result->id }}" tabindex="-1"
                                                                    role="dialog"
                                                                    aria-labelledby="viewModalLabel{{ $result->id }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content dark:bg-gray-800">
                                                                            <div class="modal-header"
                                                                                style="background-color: green; color: white;">
                                                                                <h5 class="modal-title"
                                                                                    id="viewModalLabel{{ $result->id }}">
                                                                                    Proof of Delivery
                                                                                    {{ $result->created_at }} <a
                                                                                        href="{{ asset($result->p_o_d) }}"
                                                                                        download="{{ basename($result->p_o_d) }}"
                                                                                        class="btn btn-primary">
                                                                                        <i class="fas fa-download"
                                                                                            style="font-size: 30px; color: blue;"></i>
                                                                                    </a></h5>
                                                                                <button type="button" class="close"
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                    <span
                                                                                        aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div>

                                                                                <img src="{{ asset($result->p_o_d) }}"
                                                                                    class="card-img-top" alt="No image"
                                                                                    style="object-fit: cover; height: 500px;width:500px">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{-- modal2 design view --}}
                                                                <div class="modal fade mt-11 "
                                                                    id="viewrModal{{ $result->id }}" tabindex="-1"
                                                                    role="dialog"
                                                                    aria-labelledby="viewrModalLabel{{ $result->id }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content dark:bg-gray-800">
                                                                            <div class="modal-header"
                                                                                style="background-color: green; color: white;">
                                                                                <h5 class="modal-title"
                                                                                    id="viewrModalLabel{{ $result->id }}">
                                                                                    Proof of Delivery
                                                                                    {{ $result->id }} <a
                                                                                        href="{{ asset($result->p_o_r) }}"
                                                                                        download="{{ basename($result->p_o_r) }}"
                                                                                        class="btn btn-primary">
                                                                                        <i class="fas fa-download"
                                                                                            style="font-size: 30px; color: blue;"></i>
                                                                                    </a></h5>
                                                                                <button type="button" class="close"
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                    <span
                                                                                        aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div>

                                                                                <img src="{{ asset($result->p_o_r) }}"
                                                                                    class="card-img-top" alt="No image"
                                                                                    style="object-fit: cover; height: 500px;width:500px">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>

    <script>
        function printResults() {
            var printContents = document.getElementById('printArea').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</x-app-layout>
