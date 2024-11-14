<x-app-layout>
    <x-slot name="header" class="bg-gray-400 dark:bg-green-950">
        <div class="container ">
            <div class="row ">
                <div class="col-sm">
                    <h2 class="font-semibold text-xl text-black dark:text-gray-700 leading-tight mt-2">
                        {{ __('Main Stock Transactions') }}
                    </h2>
                </div>
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
                                Search Transactions
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-blue-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 text-gray-900 dark:bg-gray-800">
                    <center>
                        <table style="border-collapse: collapse;width: 100%;"
                            class=" dark:bg-gray-800 dark:text-gray-200">

                            <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;"
                                class="bg-gray-400 dark:bg-zinc-900 dark:text-white text-black">
                                <th
                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD; text-align: center; font-size: 18px">
                                    Item
                                    Name</th>
                                <th
                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD; text-align: center; font-size: 18px">
                                    Item
                                    Number
                                </th>
                                <th
                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD; text-align: center; font-size: 18px">
                                    Quantity</th>
                                <th
                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD; text-align: center; font-size: 18px">
                                    Clinic</th>
                                <th
                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD; text-align: center; font-size: 18px">
                                    Expiry
                                    Date
                                </th>
                                <th
                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD; text-align: center; font-size: 18px">
                                    Procurer</th>
                                <th
                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD; text-align: center; font-size: 18px">
                                    Completed At:
                                </th>
                                <th
                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD; text-align: center; font-size: 18px">
                                    Recieved By:
                                </th>
                                <th
                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD; text-align: center; font-size: 18px">
                                    Recieved At:
                                </th>
                                <th
                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD; text-align: center; font-size: 18px">
                                    P_O_D
                                </th>
                                <th
                                    style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD; text-align: center; font-size: 18px">
                                    P_O_R
                                </th>
                            </tr>
                            <tr class="dark:text-gray-200">

                                @foreach ($entries as $entry)
                                    <th
                                        style="border-bottom: 1px solid #DDD; padding: 5px; border-right: 1px solid #DDD; border-left: 1px solid #DDD; text-align: left; padding-left: 10px">
                                        {{ $entry->item_name }}</th>
                                    <th
                                        style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                        {{ $entry->item_number }}</th>
                                    <th
                                        style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                        {{ $entry->item_quantity }}</th>
                                    <th
                                        style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                        {{ $entry->clinics }}</th>
                                    <th
                                        style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                        {{ $entry->expiry_date }}</th>
                                    <th
                                        style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                        {{ $entry->procurer }}</th>
                                    <th
                                        style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                        {{ $entry->created_at }}</th>
                                    <th
                                        style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                        {{ $entry->recieved_by }}</th>
                                    <th
                                        style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                        {{ $entry->updated_at }}</th>
                                    <th
                                        style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#viewModal{{ $entry->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </th>
                                    <th
                                        style="border-bottom: 1px solid #DDD; padding: 2px; border-right: 1px solid #DDD; text-align: left; padding-left: 10px">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#viewrModal{{ $entry->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </th>
                                    {{-- modal design view --}}
                                    <div class="modal fade mt-11 " id="viewModal{{ $entry->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="viewModalLabel{{ $entry->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content dark:bg-gray-800">
                                                <div class="modal-header"
                                                    style="background-color: green; color: white;">
                                                    <h5 class="modal-title" id="viewModalLabel{{ $entry->id }}">
                                                        Proof of Delivery {{ $entry->id }} <a
                                                            href="{{ asset($entry->p_o_d) }}"
                                                            download="{{ basename($entry->p_o_d) }}"
                                                            class="btn btn-primary">
                                                            <i class="fas fa-download"
                                                                style="font-size: 30px; color: blue;"></i>
                                                        </a></h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div>

                                                    <img src="{{ asset($entry->p_o_d) }}" class="card-img-top" alt="No image"
                                                        style="object-fit: cover; height: 500px;width:500px">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    {{-- modal2 design view --}}
                                    <div class="modal fade mt-11 " id="viewrModal{{ $entry->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="viewrModalLabel{{ $entry->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content dark:bg-gray-800">
                                                <div class="modal-header"
                                                    style="background-color: green; color: white;">
                                                    <h5 class="modal-title" id="viewrModalLabel{{ $entry->id }}">
                                                        Proof of Delivery {{ $entry->id }} <a
                                                            href="{{ asset($entry->p_o_r) }}"
                                                            download="{{ basename($entry->p_o_r) }}"
                                                            class="btn btn-primary">
                                                            <i class="fas fa-download"
                                                                style="font-size: 30px; color: blue;"></i>
                                                        </a></h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div>

                                                    <img src="{{ asset($entry->p_o_r) }}" class="card-img-top" alt="No image"
                                                        style="object-fit: cover; height: 500px;width:500px">
                                                </div>

                                            </div>
                                        </div>
                                    </div>

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
    <div class="modal fade mt-11 " id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header" style="background-color: green; color: white;">
                    <h5 class="modal-title" id="searchModalLabel">SEARCH</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/StockTransactions/search">
                    <div class="modal-body">
                        @csrf
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="item_name" class="dark:text-white">Item Name</label>
                                    <input type="text" id="item_name" name="item_name" placeholder="Item Name"
                                        class="form-control" style="border-radius: 9px">
                                </div>
                                <div class="col">
                                    <label for="item_number" class="dark:text-white">Item Number</label>
                                    <input type="text" id="item_number" name="item_number"
                                        placeholder="Item Number" class="form-control" style="border-radius: 9px">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="clinics" class="dark:text-white">Choose a Clinic</label>
                                    <select name="clinics" id="clinics" class="form-control"
                                        style="border-radius: 9px">
                                        <?php
                                        $clinics = DB::table('clinics')->get('clinic_name');
                                        ?>
                                        <option value="" disabled selected>Select a clinic</option>
                                        @foreach ($clinics as $clinic)
                                            <option value="{{ $clinic->clinic_name }}">{{ $clinic->clinic_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('clinics')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="procurer" class="dark:text-white">Procurer</label>
                                    <input type="text" id="procurer" name="procurer" placeholder="Procurer"
                                        class="form-control" style="border-radius: 9px">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="transaction_date_from" class="dark:text-white">Transaction
                                        Date</label>
                                    <div class="d-flex justify-content-between">
                                        <input type="date" id="transaction_date_from" name="transaction_date_from"
                                            class="form-control" style="border-radius: 9px">
                                        <span class="mx-2 align-self-center">-</span>
                                        <input type="date" id="transaction_date_to" name="transaction_date_to"
                                            class="form-control" style="border-radius: 9px">
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
