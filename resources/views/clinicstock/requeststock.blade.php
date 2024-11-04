<x-app-layout>
    <x-slot name="header">
        <div class="py-1" style="float:left;">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchrModal">
                Search
            </button>
        </div>

        <div class="py-1" style="float:right;">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchModal">
                Request stock
            </button>
        </div>

        {{-- Consolidated error and success messages --}}
        <div class="py-1" style="clear:both;">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 dark:bg-gray-800">
                    <div class="py-12 ">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100 dark:bg-gray-800">
                                    <table class=" dark:bg-gray-800 dark:text-gray-200 table table-striped table-bordered">
                                        <thead>
                                            <tr class="bg-gray-400 dark:bg-zinc-900 dark:text-white text-black">
                                                <th>Item Name</th>
                                                <th>Item Number</th>
                                                <th>Quantity</th>
                                                <th>Clinic</th>
                                                <th>Status</th>
                                                <th>Requester</th>
                                                <th>Requested At</th>
                                                <th>Handled By</th>
                                                <th>Handled At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $mypendingrequest = DB::table('stock_requests')
                                                ->where('requester', '=', auth()->user()->name)
                                                ->get();
                                            ?>
                                            
                                            @if ($mypendingrequest->isEmpty())
                                                <tr>
                                                    <td colspan="9" style="text-align: center; padding: 16px;" class="text-red-700 dark:text-red-500">
                                                        No pending requests available.
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($mypendingrequest as $prequest)
                                                    <tr class="dark:bg-gray-700 bg-gray-300 dark:text-gray-200">
                                                        <td>{{ $prequest->item_name }}</td>
                                                        <td>{{ $prequest->item_number }}</td>
                                                        <td>{{ $prequest->item_quantity }}</td>
                                                        <td>{{ $prequest->clinic }}</td>
                                                        <td>{{ $prequest->status }}</td>
                                                        <td>{{ $prequest->requester }}</td>
                                                        <td>{{ $prequest->created_at }}</td>
                                                        <td>{{ $prequest->approver }}</td>
                                                        <td>{{ $prequest->date_approved }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- request  modal design --}}
                    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog"
                        aria-labelledby="searchModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content dark:bg-gray-800">
                                    <div class="modal-header dark:bg-emerald-950 bg-green-200 dark:text-white" id="searchModalLabel">
                                        <p style="padding-top:10px;display:inline">Request</p>
                                        <button style="display:inline" type="button" class="close dark:text-white"
                                            data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <form method="POST" action="/requeststock/save" class="mt-3">
                                    <div style="padding-left:10px;padding-right:10px;width:100%">
                                        @csrf
                                        <div>
                                            <label for='item_name'>Item Name</label><br>
                                            <select name="item_name" id="item_name" class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px"
                                                onchange="autofill()">
                                                <option></option>
                                                @foreach ($drugs as $drug)
                                                    <option value="{{ $drug->item_name }}"
                                                        data-item-number="{{ $drug->item_number }}">
                                                        {{ $drug->item_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="autofillField">Item Number:</label><br>
                                            <input type="text" id="autofillField" class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px"
                                                placeholder="Item Number will be autofilled" readonly>

                                            <input type="hidden" name="item_number" id="item_number" class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px">
                                            @error('item_name')
                                                <p style="color:red;size:13px">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for='quantity'>Quantity</label><br>
                                            <input type="number" id="item_quantity" name="item_quantity" class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px"
                                                placeholder="1000" style="width: 100%;"><br>
                                            @error('item_quantity')
                                                <p style="color:red;size:13px">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="modal-footer mt-3">
                                            <button type="submit" class="btn bg-green-700 text-white dark:bg-green-500" style="width: 100%;">REQUEST STOCK</button>
                                        </div>
                                    </div>
                                </form>

                                <script>
                                    function autofill() {
                                        const dropdown = document.getElementById("item_name");
                                        const selectedOption = dropdown.options[dropdown.selectedIndex];
                                        const itemNumber = selectedOption.getAttribute("data-item-number");
                                        const autofillField = document.getElementById("autofillField");
                                        const hiddenInput = document.getElementById("item_number");
                                        // Set the autofill field based on the selected option's data
                                        autofillField.value = itemNumber || "";
                                        hiddenInput.value = itemNumber || "";
                                    }
                                </script>


                            </div>
                        </div>
                    </div>

                    {{-- modal design search --}}
                    <div class="modal fade" id="searchrModal" tabindex="-1" role="dialog"
                        aria-labelledby="searchrModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header"
                                    style="height: 50px; background-color: green; color: white; text-align: center;">
                                    <h5 class="modal-title" id="searchrModalLabel">SEARCH</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('searchrequests') }}">
                                    <div class="container p-4">
                                        @csrf
                                        <h5 class="mb-4">Search Requests</h5>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="item_name" class="form-label">Item Name</label>
                                                <input type="text" id="item_name" name="item_name"
                                                    placeholder="Enter Item Name" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="item_number" class="form-label">Item Number</label>
                                                <input type="text" id="item_number" name="item_number"
                                                    placeholder="Enter Item Number" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="clinics" class="form-label">Choose a Clinic</label>
                                                <select name="clinics" id="clinics" class="form-select">
                                                    <option value="">Select a clinic</option>
                                                    <option value="81 Baines Avenue(Harare)">81 Baines Avenue (Harare)
                                                    </option>
                                                    <option value="52 Baines Avenue(Harare)">52 Baines Avenue (Harare)
                                                    </option>
                                                    <option value="64 Cork road Avondale(Harare)">64 Cork Road Avondale
                                                        (Harare)</option>
                                                    <option value="40 Josiah Chinamano Avenue(Harare)">40 Josiah
                                                        Chinamano Avenue (Harare)</option>
                                                    <option value="Epworth Clinic(Harare)">Epworth Clinic (Harare)
                                                    </option>
                                                    <option value="Fort Street and 9th Avenue(Bulawayo)">Fort Street
                                                        and 9th Avenue (Bulawayo)</option>
                                                    <option value="Royal Arcade Complex(Bulawayo)">Royal Arcade Complex
                                                        (Bulawayo)</option>
                                                    <option value="39 6th street(GWERU)">39 6th Street (Gweru)</option>
                                                    <option value="126 Herbert Chitepo Street(Mutare)">126 Herbert
                                                        Chitepo Street (Mutare)</option>
                                                    <option value="13 Shuvai Mahofa street(Masvingo)">13 Shuvai Mahofa
                                                        Street (Masvingo)</option>
                                                </select>
                                                @error('clinics')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="status" class="form-label">Status</label>
                                                <input type="text" id="status" name="status"
                                                    placeholder="Enter Status" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="requester" class="form-label">Requester</label>
                                                <input type="text" id="requester" name="requester"
                                                    placeholder="Enter Requester Name" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="approver" class="form-label">Approver</label>
                                                <input type="text" id="approver" name="approver"
                                                    placeholder="Enter Approver Name" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="transaction_date_from" class="form-label">Transaction Date
                                                    From</label>
                                                <input type="date" id="transaction_date_from"
                                                    name="transaction_date_from" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="transaction_date_to" class="form-label">Transaction Date
                                                    To</label>
                                                <input type="date" id="transaction_date_to"
                                                    name="transaction_date_to" class="form-control">
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary"
                                                style="width: 70%;">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
