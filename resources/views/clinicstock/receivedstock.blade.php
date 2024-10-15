<x-app-layout>
    <x-slot name="header">
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('pendingstock')" :active="request()->routeIs('pendingstock')">
                {{ __('Pending Stock') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('receivedstock')" :active="request()->routeIs('receivedstock')">
                {{ __('Received Stock') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline;float:right">
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
                            Search Records
                        </button>
                    </div>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Recived stocks") }}
                 
                    <center>
                        <table style="border-collapse: collapse;width: 100%;">

                            <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Item
                                    Name</th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Item
                                    Number
                                </th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    Quantity</th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Clinic</th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Status
                                </th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    procurer</th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    Sent at:
                                </th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                     Reciever:
                                </th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    Received at:
                                </th>
                            </tr>
                           
                            <tr>
                                
                                @foreach ($rstocks as $rstock)
                                <th>{{$rstock->item_name}}</th>
                                <th>{{$rstock->item_number}}</th>
                                <th>{{$rstock->item_quantity}}</th>
                                <th>{{$rstock->clinic}}</th>
                                <th>{{$rstock->status}}</th>
                                <th>{{$rstock->procurer}}</th>
                                <th>{{$rstock->created_at}}</th>
                                <th>{{$rstock->reciever}}</th>
                                <th>{{$rstock->updated_at}}</th>
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
     <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: green; color: white;">
                    <h5 class="modal-title" id="searchModalLabel">SEARCH</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{route('searchrstock')}}">
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
    
</x-app-layout>