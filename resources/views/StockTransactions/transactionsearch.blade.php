<x-app-layout>
    <x-slot name="header">
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
        {{-- modal design --}}
        <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
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
                                        <input type="text" id="item_name" name="item_name" placeholder="Item Name" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="item_number">Item Number</label>
                                        <input type="text" id="item_number" name="item_number" placeholder="Item Number" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="clinics">Choose a Clinic</label>
                                        <select name="clinics" id="clinics" class="form-control">
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
                                        <label for="procurer">Procurer</label>
                                        <input type="text" id="procurer" name="procurer" placeholder="Procurer" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="transaction_date_from">Transaction Date</label>
                                        <div class="d-flex justify-content-between">
                                            <input type="date" id="transaction_date_from" name="transaction_date_from" class="form-control">
                                            <span class="mx-2 align-self-center">-</span>
                                            <input type="date" id="transaction_date_to" name="transaction_date_to" class="form-control">
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
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-3 text-gray-900 dark:text-gray-100">
                                    <center>
                                        @if ($results->isEmpty())
                                            <p>No results found.</p>
                                        @else
                                            <table style="border-collapse: collapse;width: 100%;">
                                                <tr
                                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                    <th
                                                        style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                        Item
                                                        Name</th>
                                                    <th
                                                        style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                        Item
                                                        Number
                                                    </th>
                                                    <th
                                                        style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                        Quantity</th>
                                                    <th
                                                        style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                        Price($USD)
                                                    </th>
                                                    <th
                                                        style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                        Clinic</th>
                                                    <th
                                                        style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                        Expiry
                                                        date
                                                    </th>
                                                    <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                        procurer</th>
                                                    <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                        Completed at:
                                                    </th>
                                                    <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                        Recieved by:
                                                    </th>
                                                    <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                                        Recieved at:
                                                    </th>
                                                </tr>
                                                <tr>
                                                    @foreach ($results as $result)
                                                    <th>{{ $result->item_name }}</th>
                                                    <th>{{ $result->item_number }}</th>
                                                    <th>{{ $result->item_quantity }}</th>
                                                    <th>{{ $result->price }}</th>
                                                    <th>{{ $result->clinics }}</th>
                                                    <th>{{ $result->expiry_date }}</th>
                                                    <th>{{ $result->procurer }}</th>
                                                    <th>{{ $result->created_at }}</th>
                                                    <th>{{ $result->recieved_by}}</th>
                                                    <th>{{ $result->updated_at }}</th>
                                                </tr>
                                        @endforeach
                                        </tr>
                                        </table>
                                        @endif
                                </div>
                            </div>
                        </div>
                    </div>
</x-app-layout>
