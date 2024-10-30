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
                                            <option value="">Select a clinic</option>
                                            <option value="81 Baines Avenue(Harare)">81 Baines Avenue(Harare)</option>
                                            <option value="52 Baines Avenue(Harare)">52 Baines Avenue(Harare)</option>
                                            <option value="64 Cork road Avondale(Harare)">64 Cork road Avondale(Harare)
                                            </option>
                                            <option value="40 Josiah Chinamano Avenue(Harare)">40 Josiah Chinamano
                                                Avenue(Harare)</option>
                                            <option value="Epworth Clinic(Harare)">Epworth Clinic(Harare)</option>
                                            <option value="Fort Street and 9th Avenue(Bulawayo)">Fort Street and 9th
                                                Avenue(Bulawayo)</option>
                                            <option value="Royal Arcade Complex(Bulawayo)">Royal Arcade
                                                Complex(Bulawayo)</option>
                                            <option value="39 6th street(GWERU)">39 6th street(GWERU)</option>
                                            <option value="126 Herbert Chitepo Street(Mutare)">126 Herbert Chitepo
                                                Street(Mutare)</option>
                                            <option value="13 Shuvai Mahofa street(Masvingo)">13 Shuvai Mahofa
                                                street(Masvingo)</option>
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
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white s overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:bg-gray-800">
                    <div class="">
                        <!-- Button to print results -->
                        <button class="btn bg-green-500 mb-3" onclick="printResults()">
                            <i class="fa fa-print"></i> Print Results
                        </button>

                        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-3 text-gray-900 dark:bg-gray-800">
                                    <center>
                                        @if ($results->isEmpty())
                                            <p>No results found.</p>
                                        @else
                                            <div id="printArea" class="print-area">
                                                <table class="table table-striped table-bordered w-100">
                                                    <thead>
                                                        <tr class="bg-gray-400 dark:bg-zinc-900 dark:text-white text-black">
                                                            <th>Item Name</th>
                                                            <th>Item Number</th>
                                                            <th>Quantity</th>
                                                            <th>Price ($USD)</th>
                                                            <th>Clinic</th>
                                                            <th>Expiry Date</th>
                                                            <th>Procurer</th>
                                                            <th>Completed At</th>
                                                            <th>Received By</th>
                                                            <th>Received At</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($results as $result)
                                                            <tr class="dark:text-gray-200">
                                                                <td>{{ $result->item_name }}</td>
                                                                <td>{{ $result->item_number }}</td>
                                                                <td>{{ $result->item_quantity }}</td>
                                                                <td>{{ $result->price }}</td>
                                                                <td>{{ $result->clinics }}</td>
                                                                <td>{{ $result->expiry_date }}</td>
                                                                <td>{{ $result->procurer }}</td>
                                                                <td>{{ $result->created_at }}</td>
                                                                <td>{{ $result->recieved_by }}</td>
                                                                <td>{{ $result->updated_at }}</td>
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
