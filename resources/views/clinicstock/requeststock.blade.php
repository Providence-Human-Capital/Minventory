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
                Request stock
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
                            <p style="padding-top:10px;display:inline">Request</p>
                            <button style="display:inline" type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <form method="POST" action="/requeststock/save">
                        <div style="padding-left:10px;padding-right:10px;width:100%">
                            @csrf
                            <div>
                                <label for='item_name'>Item Name</label><br>
                                <select name="item_name" id="item_name" style="width: 100%;">
                                    <option></option>
                                    @foreach ($drugs as $drug )
                                    <option value={{$drug}}>{{$drug}}</option>
                                    @endforeach
                                </select>
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
                            <input type="submit"
                                style="background-color: green;color:white;size:10pt;padding:5pt;margin:15pt;border-radius:5px;border-style:outset;border-color:black"
                                value="REQUEST STOCK">
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