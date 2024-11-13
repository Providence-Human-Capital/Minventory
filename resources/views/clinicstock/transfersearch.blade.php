<x-app-layout>
    <x-slot name="header">
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <!--MODAL TRANSFER BUTTON -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#transferStockModal">
                Transfer Stock
            </button>

            <!--MODAL search BUTTON -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#searchtransferStockModal">
                Search Transfer record
            </button>
        </div>
        <!--transfer modal-->
        <div class="modal fade" id="transferStockModal" tabindex="-1" role="dialog"
            aria-labelledby="transferStockModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transferStockModal">Transfer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('savetransfer') }}">
                        @csrf

                        <div class="mb-8">
                            <label for="drug_name" class="block text-gray-700 text-sm font-medium">Drug Name</label>
                            <select name="drug_name" id="drug_name" style="width: 100%;" onchange="autofill()">
                                <option></option>
                                @foreach ($drugs as $drug)
                                    <option value="{{ $drug->item_name }}" data-item-number="{{ $drug->item_number }}">
                                        {{ $drug->item_name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="autofillField">Item Number:</label><br>
                            <input type="text" id="autofillField" placeholder="Item Number will be autofilled"
                                readonly>
                            <input type="hidden" name="item_number" id="item_number">

                        </div>
                        <div class="mb-8">
                            <label for="drug_amount" class="block text-gray-700 text-sm font-medium">Drug Amount</label>
                            <input type="number" name="drug_amount" id="drug_amount"
                                class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div class="mb-8">
                            <label for="clinic_to" class="block text-gray-700 text-sm font-medium">Clinic To</label>
                            <select name="clinic_to" id="clinic_to"
                                class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <?php
                                            $clinics =DB::table('clinics')->get('clinic_name')
                                                ?>
                                            
                                            <option value="" disabled selected>Select a clinic</option>
                                            @foreach ($clinics as $clinic)
                                            <option value="{{$clinic->clinic_name}}">{{$clinic->clinic_name}}</option>
                                            @endforeach
                            </select>
                        </div>
                        <div class="text-center mt-8">
                            <button type="submit"
                                class="bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 transition duration-300 ease-in-out">
                                Submit
                            </button>
                        </div>
                    </form>



                </div>
            </div>
        </div>
        <!--search modal -->
        <div class="modal fade" id="searchtransferStockModal" tabindex="-1" role="dialog"
            aria-labelledby="searchtransferStockModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="searchtransferStockModal">Search</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Search Form -->
                    <form method="POST" action="{{ route('searchtransfer') }}" class="mb-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Drug Name -->
                            <div class="mb-4">
                                <label for="drug_name" class="block text-sm font-medium text-gray-700">Drug Name</label>
                                <select name="drug_name" id="drug_name" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="autofill()">
                                    <option></option>
                                    @foreach ($drugs as $drug)
                                        <option value="{{ $drug->item_name }}" data-item-number="{{ $drug->item_number }}">
                                            {{ $drug->item_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <!-- Clinic From -->
                            <div class="mb-4">
                                <label for="clinic_from" class="block text-sm font-medium text-gray-700">Clinic From</label>
                                <select name="clinic_from" id="clinic_from" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php
                                            $clinics =DB::table('clinics')->get('clinic_name')
                                                ?>
                                           
                                            <option value="" disabled selected>Select a clinic</option>
                                            @foreach ($clinics as $clinic)
                                            <option value="{{$clinic->clinic_name}}">{{$clinic->clinic_name}}</option>
                                            @endforeach
                                </select>
                            </div>
                    
                            <!-- Clinic To -->
                            <div class="mb-4">
                                <label for="clinic_to" class="block text-sm font-medium text-gray-700">Clinic To</label>
                                <select name="clinic_to" id="clinic_to" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php
                                            $clinics =DB::table('clinics')->get('clinic_name')
                                                ?>
                                            
                                            <option value="" disabled selected>Select a clinic</option>
                                            @foreach ($clinics as $clinic)
                                            <option value="{{$clinic->clinic_name}}">{{$clinic->clinic_name}}</option>
                                            @endforeach
                                </select>
                            </div>
                    
                            <!-- Sender & Receiver (Put them in the same row) -->
                            <div class="mb-4">
                                <label for="sender" class="block text-sm font-medium text-gray-700">Sender</label>
                                <input type="text" name="sender" id="sender" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('sender') }}" placeholder="Enter Sender Name">
                            </div>
                    
                            <div class="mb-4">
                                <label for="receiver" class="block text-sm font-medium text-gray-700">Receiver</label>
                                <input type="text" name="receiver" id="receiver" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('receiver') }}" placeholder="Enter Receiver">
                            </div>
                    
                            <!-- Date Range for Send At and Received At (Put them in the same row) -->
                            <div class="col-span-1 sm:col-span-2 lg:col-span-3 grid grid-cols-2 gap-4">
                                <!-- Send At Date Range -->
                                <div class="mb-4">
                                    <label for="send_at_start" class="block text-sm font-medium text-gray-700">Send At - Start Date</label>
                                    <input type="date" name="send_at_start" id="send_at_start" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('send_at_start') }}">
                                </div>
                    
                                <div class="mb-4">
                                    <label for="send_at_end" class="block text-sm font-medium text-gray-700">Send At - End Date</label>
                                    <input type="date" name="send_at_end" id="send_at_end" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('send_at_end') }}">
                                </div>
                    
                                <!-- Received At Date Range -->
                                <div class="mb-4">
                                    <label for="received_at_start" class="block text-sm font-medium text-gray-700">Received At - Start Date</label>
                                    <input type="date" name="received_at_start" id="received_at_start" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('received_at_start') }}">
                                </div>
                    
                                <div class="mb-4">
                                    <label for="received_at_end" class="block text-sm font-medium text-gray-700">Received At - End Date</label>
                                    <input type="date" name="received_at_end" id="received_at_end" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('received_at_end') }}">
                                </div>
                            </div>
                    
                            <!-- Submit Button -->
                            <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center">
                                <button type="submit" class="bg-blue-500 text-white p-3 rounded-md shadow-lg hover:bg-blue-700 transition duration-300 ease-in-out">Search</button>
                            </div>
                        </div>
                    </form>
                    
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
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:bg-gray-800">
                    <button class="btn btn-primary mb-3" onclick="printResults()">
                        <i class="fa fa-print"></i> Print Results
                    </button>

                    <div class="table-responsive">
                        <div id="printArea" class="print-area">
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="thead-dark bg-blue-500 text-white">
                                    <tr>
                                        <th>Drug Name</th>
                                        <th>Clinic From</th>
                                        <th>Sender</th>
                                        <th>Drug Amount</th>
                                        <th>Clinic To</th>
                                        <th>Receiver</th>
                                        <th>Send At</th>
                                        <th>Received At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $drugTransfer)
                                        <tr class="bg-white hover:bg-gray-100">
                                            <td>{{ $drugTransfer->drug_name }}</td>
                                            <td>{{ $drugTransfer->clinic_from }}</td>
                                            <td>{{ $drugTransfer->sender }}</td>
                                            <td>{{ $drugTransfer->drug_amount }}</td>
                                            <td>{{ $drugTransfer->clinic_to }}</td>
                                            <td>{{ $drugTransfer->receiver }}</td>
                                            <td>{{ $drugTransfer->created_at }}</td>
                                            <td>{{ $drugTransfer->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                    @if ($records->isEmpty())
                        <div class="text-center text-gray-600 mt-4">
                            <p>No records found.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <script>
        function autofill() {
            const dropdown = document.getElementById("drug_name");
            const selectedOption = dropdown.options[dropdown.selectedIndex];
            const itemNumber = selectedOption.getAttribute("data-item-number");
            const autofillField = document.getElementById("autofillField");
            const hiddenInput = document.getElementById("item_number");
            // Set the autofill field based on the selected option's data
            autofillField.value = itemNumber || "";
            hiddenInput.value = itemNumber || "";
        }
    </script>
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
