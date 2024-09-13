<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Main Stock Transactions') }}
        </h2>
        {{--add modal button--}}
        <div class="py-1" style="float:right;">
            @if (count($errors) >0)
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach

                </ul>
            </div>
            @endif

            @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>{{ \Session::get('success') }}</p>

            </div>
            @endif

            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchModal">
                Add Stock
            </button>
        </div>
        {{--modal design--}}
        <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="padding: 0px;height:50px">
                        <div style="color:white;width:100%;height:100%;background-color:green;top:0px;text-align:center"
                            class="modal-title" id="searchModalLabel">
                            <p style="padding-top:10px;display:inline">SEARCH</p>
                            <button style="display:inline" type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <form method="POST" action="/StockTransactions/search">
                        <div style="padding-left:10px;padding-right:10px;width:100%">
                            @csrf
                            <div class="container">
                                <div class="row align-items-start">
                                    <div class="col">
                                        <p>Item Name</p>
                                    </div>
                                    <div class="col">
                                        <p>Item Number</p>
                                    </div>
                                </div>
                                <div class="row align-items-start">
                                    <div class="col">
                                        <input type="text" id="item_name" name="item_name" placeholder="ITEM NAME">
                                    </div>
                                    <div class="col">
                                        <input type="text" id="item_number" name="item_number"
                                            placeholder="ITEM NUMBER">
                                    </div>
                                </div>
                                <div class="row align-items-start">
                                    <div class="col">
                                        <p>Main Stock</p>
                                    </div>
                                    <div class="col">
                                        <p>Procurer</p>
                                    </div>
                                </div>
                                <div class="row align-items-start">
                                    <div class="col">
                                        <label for="clinics">Choose a Clinic</label><br>
                                        <select name="clinics" id="clinics" style="width: 100%;">
                                            <option value=></option>
                                            <option value="81 Baines Avenue(Harare)">81 Baines Avenue(Harare)</option>
                                            <option value="52 Baines Avenue(Harare)">52 Baines Avenue(Harare)</option>
                                            <option value="64 Cork road Avondale(Harare)">64 Cork road Avondale(Harare)
                                            </option>
                                            <option value="40 Josiah Chinamano Avenue(Harare)">40 Josiah Chinamano
                                                Avenue(Harare)</option>
                                            <option value="Epworth Clinic(Harare)">Epworth Clinic(Harare)</option>
                                            <option value="Fort Street and 9th Avenue(Bulawayo)">Fort Street and 9th
                                                Avenue(Bulawayo)</option>
                                            <option value="Royal Arcade Complex(Bulawayo)">Royal Arcade
                                                Complex(Bulawayo)</option>
                                            <option value="39 6th street(GWERU)">39 6th street(GWERU)</option>
                                            <option value="126 Herbert Chitepo Street(Mutare)">126 Herbert Chitepo
                                                Street(Mutare)</option>
                                            <option value="13 Shuvai Mahofa street(Masvingo)">13 Shuvai Mahofa
                                                street(Masvingo)</option>
                                        </select><br>
                                        @error('clinics')
                                        <p style="color:red;size:13px">{{ $message }}</p>
                                        @enderror
                                        </label><br>
                                    </div>
                                    <div class="col">
                                        <input type="text" id="procurer" name="procurer" placeholder="PROCURER"><br>
                                    </div>
                                </div><br>
                                <div class="row align-items-start">
                                    <div class="col">
                                        <p>Amount Procured</p>
                                    </div>
                                </div>
                                <div class="row align-items-start">
                                    <div class="col">
                                        <input type="number" id="item_quantity_min" name="item_quantity_min"
                                            placeholder="PROCURER MIN" placeholder="Min">
                                    </div>
                                    <p>-</p>
                                    <div class="col">
                                        <input type="number" id="item_quantity_max" name="item_quantity_max"
                                            placeholder="procurer MAX" placeholder="Max">
                                    </div>
                                </div>
                                <div class="row align-items-start">
                                    <div class="col">
                                        <p>Price of Procurement</p>
                                    </div>
                                </div>
                                <div class="row align-items-start">
                                    <div class="col">
                                        <input type="number" id="price_min" name="price_min" placeholder="priceMax">
                                    </div>
                                    <p>-</p>
                                    <div class="col">
                                        <input type="number" id="price_max" name="price_max" placeholder="priceMin">
                                    </div>
                                </div>
                                <div class="row align-items-start">
                                    <div class="col">
                                        <p>Expiration date</p>
                                    </div>
                                </div>
                                <div class="row align-items-start">
                                    <p>From</p>
                                    <div class="col">
                                        <input type="date" id="expiry_date_from" name="expiry_date_from">
                                    </div>
                                    <p>To</p>
                                    <div class="col">
                                        <input type="date" id="expiry_date_to" name="expiry_date_to">
                                    </div>
                                </div>
                                <div class="row align-items-start">
                                    <div class="col">
                                        <p>Transaction date</p>
                                    </div>
                                </div>
                                <div class="row align-items-start">
                                    <p>From</p>
                                    <div class="col">
                                        <input type="date" id="transaction_date_from" name="transaction_date_from">
                                    </div>
                                    <p>To</p>
                                    <div class="col">
                                        <input type="date" id="transaction_date_to" name="transaction_date_to">
                                    </div>
                                </div>

                            </div>

                            <input type="submit"
                                style="background-color: rgb(0, 17, 128);color:white;size:10pt;padding:5pt;margin:15pt;border-radius:5px;border-style:outset;border-color:black;width:70%"
                                value="Search">
                        </div>
                    </form>


                </div>
            </div>
        </div>



    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 text-gray-900 dark:text-gray-100">
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
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Price($USD)
                                </th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Clinic</th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Expiry
                                    date
                                </th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    procurer</th>
                                <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    Completed at:
                                </th>
                            </tr>
                            <tr>
                                @foreach ($entries as $entry)
                                <th>{{$entry->item_name}}</th>
                                <th>{{$entry->item_number}}</th>
                                <th>{{$entry->item_quantity}}</th>
                                <th>{{$entry->price}}</th>
                                <th>{{$entry->clinics}}</th>
                                <th>{{$entry->expiry_date}}</th>
                                <th>{{$entry->procurer}}</th>
                                <th>{{$entry->created_at}}</th>
                                
                            </tr>



                            @endforeach
                            </tr>
                        </table>
                    </center>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>