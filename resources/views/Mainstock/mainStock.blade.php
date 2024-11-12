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
                    <div style="display:inline">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>
                            </div>
                        @endif



                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                            Add New Stock
                        </button>
                    </div>
                </div>
                <div class="col-sm">
                    <form action="{{ route('searchmainstock') }}" method="GET">
                        <input type="text" name="isearch" id="isearch" value="{{ old('isearch') }}">
                        <button type="submit">Search</button>
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
                    <table style="border-collapse: collapse;width: 100%;">
                        <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Item Name</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Item Number</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Quantity</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Action</th>
                        </tr>


                        @foreach ($stock as $stocks)
                            <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $stocks->item_name }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $stocks->item_number }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $stocks->item_quantity }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{-- modal button to Add --}}

                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#addStockModal{{ $stocks->id }}"><i class="fas fa-warehouse"></i>
                                        Add Stock
                                    </button>
                </div>
                {{-- modal button to distribute --}}
                <div style="display:inline">
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#distributeStockModal{{ $stocks->id }}"><i class="fas fa-shipping-fast"></i>
                        Distribute Stock
                    </button>
                </div>
                </td>
                </tr>

                {{-- addStock model design start here --}}
                <div class="modal fade" id="addStockModal{{ $stocks->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="addStockModalLabel{{ $stocks->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 0px;height:50px">
                                <div style="color:white;width:100%;height:100%;background-color:green;top:0px;text-align:center"
                                    class="modal-title" id="addStockModalLabel{{ $stocks->id }}">
                                    <p style="padding-top:10px;display:inline">ADD TO STOCK</p>
                                    <button style="display:inline" type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <form method="POST" action="/mainstock/{{ $stocks->id }}">
                                <div style="padding-left:10px;padding-right:10px;width:100%">
                                    @csrf
                                    @method('patch')
                                    <div>
                                        <label for='item_name'>Item Name</label><br>
                                        <input type="text" id="item_name" name="item_name"
                                            value={{ $stocks->item_name }} style="width: 100%;"><br>
                                        @error('item_name')
                                            <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='quantity'>Quantity </label><br>
                                        <input type="number" id="item_quantity" name="item_quantity" placeholder="1000"
                                            style="width: 100%;"><br>
                                        @error('item_quantity')
                                            <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='item_number'>Item Number</label><br>
                                        <input type="text" id="item_number" name="item_number"
                                            value={{ $stocks->item_number }} style="width: 100%;"><br>
                                        @error('item_number')
                                            <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='batch_number'>Batch Number</label><br>
                                        <input type="text" id="batch_number" name="batch_number"
                                            style="width: 100%;"><br>
                                        @error('batch_number')
                                            <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='price'>Price</label><br>
                                        <label for="$">
                                            <input type="number" id="price" name="price" placeholder="1000"
                                                style="width: 100%;">
                                            @error('price')
                                                <p style="color:red;size:13px">{{ $message }}</p>
                                            @enderror
                                        </label><br>
                                    </div>
                                    <div>
                                        <label for='expiry_date'>Expiry Date</label><br>
                                        <input type="date" id="expiry_date" name="expiry_date"
                                            placeholder="B1992XC" style="width: 100%;"><br>
                                    </div>

                                    <input type="submit"
                                        style="background-color: green;color:white;size:10pt;padding:5pt;margin:15pt;border-radius:5px;border-style:outset;border-color:black"
                                        value="ADD TO STOCK">
                                </div>
                            </form>


                        </div>
                    </div>
                </div>

                {{-- DistributeStock model design start here --}}
                <div class="modal fade" id="distributeStockModal{{ $stocks->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="distributeStockModalLabel{{ $stocks->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="distributeModalLabel{{ $stocks->id }}">
                                    Distribute</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form method="POST" action="/mainstock/dis/{{ $stocks->id }}">
                                <div style="padding-left:10px;padding-right:10px;width:100%">
                                    @csrf
                                    @method('patch')
                                    <div>
                                        <label for='item_name'>Item Name</label><br>
                                        <input type="text" id="item_name" name="item_name"
                                            value="{{ $stocks->item_name }}" style="width: 100%;"><br>
                                        @error('item_name')
                                            <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='quantity'>Quantity </label><br>
                                        <input type="number" id="item_quantity" name="item_quantity"
                                            placeholder="Must not exceed existing Stock" style="width: 100%;"
                                            max="{{ $stocks->item_quantity }}"><br>
                                        @error('item_quantity')
                                            <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='item_number'>Item Number</label><br>
                                        <input type="text" id="item_number" name="item_number"
                                            value={{ $stocks->item_number }} style="width: 100%;"><br>
                                        @error('item_number')
                                            <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="clinics">Choose a Clinic</label><br>
                                        <select name="clinics" id="clinics" style="width: 100%;">
                                            <option value="81 Baines Avenue(Harare)">81 Baines
                                                Avenue(Harare)
                                            </option>
                                            <option value="52 Baines Avenue(Harare)">52 Baines
                                                Avenue(Harare)
                                            </option>
                                            <option value="64 Cork road Avondale(Harare)">64 Cork road
                                                Avondale(Harare)</option>
                                            <option value="40 Josiah Chinamano Avenue(Harare)">40 Josiah
                                                Chinamano Avenue(Harare)</option>
                                            <option value="Epworth Clinic(Harare)">Epworth Clinic(Harare)
                                            </option>
                                            <option value="Fort Street and 9th Avenue(Bulawayo)">Fort
                                                Street and
                                                9th Avenue(Bulawayo)</option>
                                            <option value="Royal Arcade Complex(Bulawayo)">Royal Arcade
                                                Complex(Bulawayo)</option>
                                            <option value="39 6th street(GWERU)">39 6th street(GWERU)
                                            </option>
                                            <option value="126 Herbert Chitepo Street(Mutare)">126 Herbert
                                                Chitepo Street(Mutare)</option>
                                            <option value="13 Shuvai Mahofa street(Masvingo)">13 Shuvai
                                                Mahofa
                                                street(Masvingo)</option>
                                        </select><br>
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
                @endforeach
                </table>

                {{-- add newStock model design start here --}}
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModal"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 0px;height:50px">
                                <div style="color:white;width:100%;height:100%;background-color:green;top:0px;text-align:center"
                                    class="modal-title" id="addModal">
                                    <p style="padding-top:10px;display:inline">ADD TO STOCK</p>
                                    <button style="display:inline" type="button" class="close"
                                        data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <form method="POST" action="/mainstock">
                                <div style="padding-left:10px;padding-right:10px;width:100%">
                                    @csrf
                                    <div>
                                        <label for='item_name'>Item Name</label><br>
                                        <input type="text" id="item_name" name="item_name"
                                            style="width: 100%;"><br>
                                        @error('item_name')
                                            <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='quantity'>Quantity </label><br>
                                        <input type="number" id="item_quantity" name="item_quantity"
                                            placeholder="1000" style="width: 100%;"><br>
                                        @error('item_quantity')
                                            <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for='item_number'>Item Number</label><br>
                                        <input type="text" id="item_number" name="item_number"
                                            style="width: 100%;"><br>
                                        @error('item_number')
                                            <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <input type="submit"
                                        style="background-color: green;color:white;size:10pt;padding:5pt;margin:15pt;border-radius:5px;border-style:outset;border-color:black"
                                        value="ADD TO STOCK">
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
