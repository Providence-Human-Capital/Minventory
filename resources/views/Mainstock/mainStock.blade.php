<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                        Add New Stock Item
                    </button>
                </div>
                <div class="col-sm">
                    <div style="display:inline">

                        <a href="{{ route('bulkform') }}"><button type="button" class="btn btn-success">
                                <i class="fas fa-shipping-fast"></i>
                                Distribute Stock
                            </button></a>
                        <a href="{{ route('bulkformadd') }}"><button type="button" class="btn btn-primary">
                                <i class="fas fa-warehouse"></i>
                                Add Stock
                            </button></a>
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
                                                <button style="display:inline" type="button" class="close"
                                                    data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                        <form method="POST" action="/mainstock/{{ $stocks->id }}"
                                            enctype="multipart/form-data">
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
                                                    <input type="number" id="item_quantity" name="item_quantity"
                                                        placeholder="1000" style="width: 100%;"><br>
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
                                                        <input type="number" id="price" name="price"
                                                            placeholder="1000" style="width: 100%;">
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
                                                <!-- Image Upload -->
                                                <div>
                                                    <label for="item_image">Upload Image</label><br>
                                                    <input type="file" id="item_image" name="item_image"
                                                        accept="image/*" style="width: 100%;"><br>
                                                    @error('item_image')
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

                            {{-- DistributeStock model design start here --}}
                            <div class="modal fade" id="distributeStockModal{{ $stocks->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="distributeStockModalLabel{{ $stocks->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="distributeModalLabel{{ $stocks->id }}">
                                                Distribute</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form method="POST" action="/mainstock/dis/{{ $stocks->id }}"
                                            enctype="multipart/form-data">
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
                                                        placeholder="Must not exceed existing Stock"
                                                        style="width: 100%;" max="{{ $stocks->item_quantity }}"><br>
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
                                                    <select name="clinics" id="clinics" style="width: 100%;"
                                                        required>
                                                        <?php
                                                        $clinics = DB::table('clinics')->get('clinic_name');
                                                        ?>

                                                        <option value="" disabled selected>Select a clinic
                                                        </option>
                                                        @foreach ($clinics as $clinic)
                                                            <option value="{{ $clinic->clinic_name }}">
                                                                {{ $clinic->clinic_name }}
                                                            </option>
                                                        @endforeach
                                                    </select><br>
                                                    @error('clinics')
                                                        <p style="color:red;size:13px">{{ $message }}</p>
                                                    @enderror
                                                    </label><br>

                                                    <!-- Image Upload -->
                                                    <div>
                                                        <label for="item_image">Upload Image</label><br>
                                                        <input type="file" id="item_image" name="item_image"
                                                            accept="image/*" style="width: 100%;"><br>
                                                        @error('item_image')
                                                            <p style="color:red;size:13px">{{ $message }}</p>
                                                        @enderror
                                                    </div>

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
                                <form method="POST" action="/mainstock" enctype="multipart/form-data">
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
