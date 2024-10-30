<x-app-layout >
    <x-slot name="header" class="bg-gray-400 dark:bg-green-950">
            <div class="container ">
            <div class="row " >
                <div class="col-sm">
                    <h2 class="font-semibold text-xl text-black dark:text-gray-100 leading-tight mt-2">
                        {{ __('Main Stock Transactions') }}
                    </h2>
                </div>
                <div class="col-sm">

                    <div class="col-sm">
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

                            <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#searchModal">
                                Search Transactions
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-blue-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 text-gray-900 dark:bg-gray-800">
                    <center>
                        <table class=" dark:bg-gray-800 dark:text-gray-200 table table-striped table-bordered">
                        <thead>
                            <tr class="bg-gray-400 dark:bg-zinc-900 dark:text-white text-black">
                                <th>Item
                                    Name</th>
                                <th>Item
                                    Number
                                </th>
                                <th>
                                    Quantity</th>
                                <th>Price ($USD)
                                </th>
                                <th>Clinic</th>
                                <th>Expiry
                                    Date
                                </th>
                                <th>
                                    Procurer</th>
                                <th>
                                    Completed At:
                                </th>
                                <th>
                                    Recieved By:
                                </th>
                                <th>
                                    Recieved At:
                                </th>
                            </tr>
                            </thead>
                            @foreach ($entries as $entry)
                            <tr class="dark:bg-gray-700 bg-gray-300 dark:text-gray-200">
                                    <td >{{ $entry->item_name }}</td>
                                    <td>{{ $entry->item_number }}</td>
                                    <td>{{ $entry->item_quantity }}</td>
                                    <td>{{ $entry->price }}</td>
                                    <td>{{ $entry->clinics }}</td>
                                    <td>{{ $entry->expiry_date }}</td>
                                    <td>{{ $entry->procurer }}</td>
                                    <td>{{ $entry->created_at }}</td>
                                    <td>{{ $entry->recieved_by}}</td>
                                    <td>{{ $entry->updated_at }}</td>
                                
                            </tr>
                            @endforeach
                            </tr>
                        </table>
                    </center>
                </div>
            </div>
        </div>
    </div>

    {{-- modal design search --}}
    <div class="modal fade mt-11 " id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header dark:bg-emerald-950 bg-green-200 dark:text-white" >
                    <h5 class="modal-title " id="searchModalLabel">SEARCH</h5>
                    <button type="button" class="close dark:text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/StockTransactions/search">
                    <div class="modal-body">
                        @csrf
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="item_name" class="dark:text-white">Item Name</label>
                                    <input type="text" id="item_name" name="item_name" placeholder="Item Name" class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px">
                                </div>
                                <div class="col">
                                    <label for="item_number" class="dark:text-white">Item Number</label>
                                    <input type="text" id="item_number" name="item_number" placeholder="Item Number" class="form-control dark:bg-gray-700 dark:text-white"  style="border-radius: 9px">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="clinics" class="dark:text-white">Choose a Clinic</label>
                                    <select name="clinics" id="clinics" class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px">
                                        <option value="">Select a clinic</option>
                                        <option value="81 Baines Avenue(Harare)">81 Baines Avenue(Harare)</option>
                                        <option value="52 Baines Avenue(Harare)">52 Baines Avenue(Harare)</option>
                                        <option value="64 Cork road Avondale(Harare)">64 Cork road Avondale(Harare)</option>
                                        <option value="40 Josiah Chinamano Avenue(Harare)">40 Josiah Chinamano Avenue(Harare)</option>
                                        <option value="Epworth Clinic(Harare)">Epworth Clinic(Harare)</option>
                                        <option value="Fort Street and 9th Avenue(Bulawayo)">Fort Street and 9th Avenue(Bulawayo)</option>
                                        <option value="Royal Arcade Complex(Bulawayo)">Royal Arcade Complex(Bulawayo)</option>
                                        <option value="39 6th street(GWERU)">39 6th street(GWERU)</option>
                                        <option value="126 Herbert Chitepo Street(Mutare)">126 Herbert Chitepo Street(Mutare)</option>
                                        <option value="13 Shuvai Mahofa street(Masvingo)">13 Shuvai Mahofa street(Masvingo)</option>
                                    </select>
                                    @error('clinics')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="procurer" class="dark:text-white">Procurer</label>
                                    <input type="text" id="procurer" name="procurer" placeholder="Procurer" class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="transaction_date_from" class="dark:text-white">Transaction Date</label>
                                    <div class="d-flex justify-content-between">
                                        <input type="date" id="transaction_date_from" name="transaction_date_from" class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px">
                                        <span class="mx-2 align-self-center">-</span>
                                        <input type="date" id="transaction_date_to" name="transaction_date_to" class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-green-700 text-white dark:bg-green-500" style="width: 100%;">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
