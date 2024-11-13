<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Main Stock') }}
                    </h2>
                </div>
                <div class="col-sm">
                </div>
                <div class="col-sm">
                    <form action="{{route ('searchmainstock')}}" method="GET">
                        <input type="text" name="isearch" id="isearch" placeholder="Search items"
                            value="{{ old('isearch')}}">
                        <button type="submit" class="btn btn-primary">Search</button>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
    
                    <table style="border-collapse: collapse;width: 100%;">
                        <thead>
                            <tr style="text-align: left; border-bottom: 1px solid #DDD;">
                                <th style="padding: 8px;">Item Name</th>
                                <th style="padding: 8px;">Item Number</th>
                                <th style="padding: 8px;">Quantity</th>
                                <th style="padding: 8px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($search as $searchs)
                                <tr style="border-bottom: 1px solid #DDD;">
                                    <td style="padding: 8px;">{{ $searchs->item_name }}</td>
                                    <td style="padding: 8px;">{{ $searchs->item_number }}</td>
                                    <td style="padding: 8px;">{{ $searchs->item_quantity }}</td>
                                    <td style="padding: 8px;">
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                                data-target="#addStockModal{{ $searchs->id }}">
                                            Add Stock
                                        </button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#distributeStockModal{{ $searchs->id }}">
                                            Distribute Stock
                                        </button>
                                    </td>
                                </tr>
    
                                {{-- Add Stock Modal --}}
                                <div class="modal fade" id="addStockModal{{ $searchs->id }}" tabindex="-1" role="dialog"
                                     aria-labelledby="addStockModalLabel{{ $searchs->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: green; color: white;">
                                                <h5 class="modal-title" id="addStockModalLabel{{ $searchs->id }}">
                                                    ADD TO STOCK
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" action="/mainstock/{{ $searchs->id }}">
                                                @csrf
                                                @method('patch')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="item_name">Item Name</label>
                                                        <input type="text" id="item_name" name="item_name"
                                                               value="{{ $searchs->item_name }}" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="item_quantity">Quantity</label>
                                                        <input type="number" id="item_quantity" name="item_quantity"
                                                               class="form-control" placeholder="1000">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="item_number">Item Number</label>
                                                        <input type="text" id="item_number" name="item_number"
                                                               value="{{ $searchs->item_number }}" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="price">Price</label>
                                                        <input type="number" id="price" name="price" class="form-control"
                                                               placeholder="1000">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="expiry_date">Expiry Date</label>
                                                        <input type="date" id="expiry_date" name="expiry_date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">ADD TO STOCK</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
    
                                {{-- Distribute Stock Modal --}}
                                <div class="modal fade" id="distributeStockModal{{ $searchs->id }}" tabindex="-1" role="dialog"
                                     aria-labelledby="distributeStockModalLabel{{ $searchs->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Distribute Stock</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" action="/mainstock/dis/{{ $searchs->id }}">
                                                @csrf
                                                @method('patch')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="item_name">Item Name</label>
                                                        <input type="text" id="item_name" name="item_name"
                                                               value="{{ $searchs->item_name }}" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="item_quantity">Quantity</label>
                                                        <input type="number" id="item_quantity" name="item_quantity"
                                                               class="form-control" placeholder="1000">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="item_number">Item Number</label>
                                                        <input type="text" id="item_number" name="item_number"
                                                               value="{{ $searchs->item_number }}" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="clinics">Choose a Clinic</label>
                                                        <select name="clinics" id="clinics" class="form-control">
                                                            <?php
                                            $clinics =DB::table('clinics')->get('clinic_name')
                                                ?>
                                            
                                            <option value="" disabled selected>Select a clinic</option>
                                            @foreach ($clinics as $clinic)
                                            <option value="{{$clinic->clinic_name}}">{{$clinic->clinic_name}}</option>
                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Distribute TO STOCK</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>