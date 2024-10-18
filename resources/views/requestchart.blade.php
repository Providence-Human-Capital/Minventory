<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
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
                    <p>Current stock of {{ $requestedname }} at {{ $requestedclinic }}: {{ $currentclinicstock }}</p>
                    <p>Current stock of {{ $requestedname }} in Mainstock: {{ $maincurrentstock }}</p>

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
                <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog"
                    aria-labelledby="rejectModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="rejectModal">Rejection Email</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form action="{{ route('sendEmail') }}" method="POST" style="margin: 10pt">
                                @csrf
                                <input type="hidden" name="requestedId" value="{{$requestedid}}">
                                <div>
                                    
                                    <label for="subject">Subject:</label><br>
                                    <input type="text" id="subject" name="subject" required style="width: 100%;">
                                </div>
                                <div>
                                    <label for="message">Message:</label><br>
                                    <textarea id="message" name="message" required style="width: 100%;" rows="5"></textarea>
                                </div>
                                <input type="submit" class="btn btn-primary" value="Send Email" style="margin-top: 10px;">
                            </form>

                        </div>
                    </div>
                </div>
                {{-- DistributeStock model design start here --}}
                <div class="modal fade" id="distributeStockModal1" tabindex="-1" role="dialog"
                    aria-labelledby="distributeStockModalLabel1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="distributeModalLabel1">Distribute</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form method="POST" action="/mainstock/dis/{{$requestedid}}">
                                <div style="padding-left:10px;padding-right:10px;width:100%">
                                    @csrf
                                    @method('patch')
                                    <div>
                                        <input type="hidden" id="requestid" name="requestid"
                                            value={{$requestedid}} 
                                        style="width: 100%;">
                                        <label for='item_name'>Item Name</label><br>
                                        <input type="text" id="item_name" name="item_name"
                                            value={{$requestedname}}
                                        style="width: 100%;"><br>
                                        @error('item_name')
                                        <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='quantity'>Quantity </label><br>
                                        <input type="number" id="item_quantity" name="item_quantity"
                                            value="{{$requestedquantity}}" style="width: 100%;"
                                            ><br>
                                        @error('item_quantity')
                                        <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='item_number'>Item Number</label><br>
                                        <input type="text" id="item_number" name="item_number"
                                            value={{$requestednumber}}
                                        style="width: 100%;"><br>
                                        @error('item_number')
                                        <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="clinics">Clinic</label>:
                                        <input type="text " id="clinics" value="{{$requestedclinic}}" readonly>
                                        @error('clinics')
                                        <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                        </label><br>
                                    </div>

                                    <input type="submit"
                                        style="background-color: green;color:white;size:10pt;padding:5pt;margin:15pt;border-radius:5px;border-style:outset;border-color:black"
                                        value="Distribute TO STOCK">
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
