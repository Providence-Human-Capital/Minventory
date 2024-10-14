<x-app-layout>
    <x-slot name="header">
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('showrequests')" :active="request()->routeIs('showrequests')">
                {{ __('Pending Requests') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('showarequests')" :active="request()->routeIs('showarequests')">
                {{ __('Approved Requests') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('showdrequests')" :active="request()->routeIs('showdrequests')">
                {{ __('Denied Requests') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('showallrequests')" :active="request()->routeIs('showallrequests')">
                {{ __('Search Requests') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="float:right">
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
                                Search Requests
                            </button>
        </div>

        
    </x-slot>

    
    {{-- modal design search --}}
    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="height: 50px; background-color: green; color: white; text-align: center;">
                    <h5 class="modal-title" id="searchModalLabel">SEARCH</h5>
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
                                <input type="text" id="item_name" name="item_name" placeholder="Enter Item Name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="item_number" class="form-label">Item Number</label>
                                <input type="text" id="item_number" name="item_number" placeholder="Enter Item Number" class="form-control">
                            </div>
                        </div>
    
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="clinics" class="form-label">Choose a Clinic</label>
                                <select name="clinics" id="clinics" class="form-select">
                                    <option value="">Select a clinic</option>
                                    <option value="81 Baines Avenue(Harare)">81 Baines Avenue (Harare)</option>
                                    <option value="52 Baines Avenue(Harare)">52 Baines Avenue (Harare)</option>
                                    <option value="64 Cork road Avondale(Harare)">64 Cork Road Avondale (Harare)</option>
                                    <option value="40 Josiah Chinamano Avenue(Harare)">40 Josiah Chinamano Avenue (Harare)</option>
                                    <option value="Epworth Clinic(Harare)">Epworth Clinic (Harare)</option>
                                    <option value="Fort Street and 9th Avenue(Bulawayo)">Fort Street and 9th Avenue (Bulawayo)</option>
                                    <option value="Royal Arcade Complex(Bulawayo)">Royal Arcade Complex (Bulawayo)</option>
                                    <option value="39 6th street(GWERU)">39 6th Street (Gweru)</option>
                                    <option value="126 Herbert Chitepo Street(Mutare)">126 Herbert Chitepo Street (Mutare)</option>
                                    <option value="13 Shuvai Mahofa street(Masvingo)">13 Shuvai Mahofa Street (Masvingo)</option>
                                </select>
                                @error('clinics')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">status</label>
                                <input type="text" id="status" name="status" placeholder="Enter status" class="form-control">
                            </div>
                            
                           
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="requester" class="form-label">Requester</label>
                                <input type="text" id="requester" name="requester" placeholder="Enter Requester Name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="approver" class="form-label">Approver</label>
                                <input type="text" id="approver" name="approver" placeholder="Enter Approver Name" class="form-control">
                            </div>
                        </div>
    
                        <div class="row mb-4">
                            <div class="col">
                                <label for="transaction_date_from" class="form-label">Transaction Date</label>
                                <div class="d-flex justify-content-between">
                                    <div class="w-50 pe-2">
                                        <input type="date" id="transaction_date_from" name="transaction_date_from" class="form-control">
                                    </div>
                                    <div class="w-50 ps-2">
                                        <input type="date" id="transaction_date_to" name="transaction_date_to" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="width: 70%;">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
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
                                Requester</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                Requested at:
                            </th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                              Handled by
                            </th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                Handled at:
                            </th>
                        </tr>
                        <tr>
                            @foreach ($results as $request)
                            <th>{{$request->item_name}}</th>
                            <th>{{$request->item_number}}</th>
                            <th>{{$request->item_quantity}}</th>
                            <th>{{$request->clinic}}</th>
                            <th>{{$request->status}}</th>
                            <th>{{$request->requester}}</th>
                            <th>{{$request->created_at}}</th>
                            <th>{{$request->approver}}</th>
                            <th>{{$request->date_approved}}</th>
                        </tr>



                        @endforeach
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>