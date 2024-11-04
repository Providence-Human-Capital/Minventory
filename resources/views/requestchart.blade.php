<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 ">
                    <div>
                        <div style="width: 80%; margin: auto;">
                            <canvas id="barChart"></canvas>
                        </div>
                        <script>
                            var ctx = document.getElementById('barChart').getContext('2d');
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: @json($data['labels']),
                                    datasets: [{
                                        label: "{{$requestedname}} sent to {{$requestedclinic}}",
                                        data: @json($data['data']),
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        </script>
                    </div>
                    <p class="dark:text-black">Current stock of {{ $requestedname }} at {{ $requestedclinic }}: {{ $currentclinicstock }}</p>
                    <p class="dark:text-black">Current stock of {{ $requestedname }} in Mainstock: {{ $maincurrentstock }}</p>

                {{--modal button to distribute--}}
                <div style="display:inline">
                    @if (count($errors) >0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach

                        </ul>
                    </div>
                    @endif



                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#distributeStockModal1">
                        Distribute Stock
                    </button>
                </div>

                 {{--modal button to Reject--}}
                 <div style="display:inline">
                    @if (count($errors) >0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach

                        </ul>
                    </div>
                    @endif



                    <button type="button" class="btn btn-danger" data-toggle="modal"
                        data-target="#rejectModal">
                        Reject Request
                    </button>
                </div>

                {{-- Reject model design start here --}}
                <div class="modal fade dark:text-white text-black" id="rejectModal" tabindex="-1" role="dialog"
                    aria-labelledby="rejectModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content dark:bg-gray-800">
                            <div class="modal-header dark:bg-red-800 bg-red-200 dark:text-white">
                                <h5 class="modal-title" id="rejectModal">Rejection Email</h5>
                                <button type="button" class="close dark:text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form action="{{ route('sendEmail') }}" method="POST" style="margin: 10pt">
                                @csrf
                                <input type="hidden" name="requestedId" value="{{$requestedid}}">
                                <div>
                                    
                                    <label for="subject">Subject:</label><br>
                                    <input type="text" id="subject" name="subject" required class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px">
                                </div>
                                <div>
                                    <label for="message">Message:</label><br>
                                    <textarea id="message" name="message" required class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px" rows="5"></textarea>
                                </div>

                                <div class="modal-footer mt-3">
                        <button type="submit" class="btn bg-red-700 text-white dark:bg-red-500" style="width: 100%;">REJECT REQUEST</button>
                    </div>
                            </form>

                        </div>
                    </div>
                </div>

                {{-- DistributeStock model design start here --}}
                <div class="modal fade dark:text-white text-black" id="distributeStockModal1" tabindex="-1" role="dialog"
                    aria-labelledby="distributeStockModalLabel1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content dark:bg-gray-800">
                            <div class="modal-header dark:bg-blue-900 bg-blue-200 dark:text-white">
                                <h5 class="modal-title" id="distributeModalLabel1">Distribute</h5>
                                <button type="button" class="close dark:text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form method="POST" action="/mainstock/dis/{{$requestedid}}" class="mt-2">
                                <div style="padding-left:10px;padding-right:10px;width:100%">
                                    @csrf
                                    @method('patch')
                                    <div>
                                        <input type="hidden" id="requestid" name="requestid"
                                            value={{$requestedid}} 
                                            class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px">
                                        <label for='item_name'>Item Name</label><br>
                                        <input type="text" id="item_name" name="item_name"
                                            value={{$requestedname}}
                                            class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px"><br>
                                        @error('item_name')
                                        <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='quantity'>Quantity </label><br>
                                        <input type="number" id="item_quantity" name="item_quantity"
                                            value="{{$requestedquantity}}"class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px"
                                            ><br>
                                        @error('item_quantity')
                                        <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='item_number'>Item Number</label><br>
                                        <input type="text" id="item_number" name="item_number"
                                            value={{$requestednumber}}
                                            class="form-control dark:bg-gray-700 dark:text-white" style="border-radius: 9px"><br>
                                        @error('item_number')
                                        <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="clinics">Clinic</label>:
                                        <input type="text " id="clinics" value="{{$requestedclinic}}" readonly class="form-control dark:bg-gray-700 dark:text-black" style="border-radius: 9px">
                                        @error('clinics')
                                        <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                        </label><br>
                                    </div>

                                    <div class="modal-footer">
                        <button type="submit" class="btn bg-blue-700 text-white dark:bg-blue-500" style="width: 100%;">DISTRIBUTE TO STOCK</button>
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
