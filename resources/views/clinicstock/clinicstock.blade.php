<x-app-layout>
    <x-slot name="header">
        <center>
            <div class="col-sm">
                <form action="{{ route('searchclinicstock') }}" method="GET">
                    <input type="text" name="isearch" id="isearch" value="{{ old('isearch') }}">
                    <button type="submit" class="btn btn-success">Search</button>
                </form>
            </div>
        </center>

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
                <div class="p-6 text-gray-900 dark:text-gray-100 dark:bg-gray-800">
                    <table style="border-collapse: collapse;width: 100%;" class="dark:bg-gray-800 dark:text-gray-200 table table-striped table-bordered">
                    <thead>
                        <tr class="bg-gray-400 dark:bg-zinc-900 dark:text-white text-black">
                            <th>Item Name</th>
                            <th>Item Number</th>
                            <th>Quantity</th>
                            <th></th>

                        </tr>
                        </thead>

                        @foreach ($clinicstock as $stock)
                            <tr class="dark:bg-gray-700 bg-gray-300 dark:text-gray-200">
                                <td>
                                    {{ $stock->item_name }}
                                </td>
                                <td>
                                    {{ $stock->item_number }}
                                </td>
                                <td>
                                    {{ $stock->item_quantity }}
                                </td>
                                <td>
                                    <a><i class="fas fa-hand-holding-medical"></i></a>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchrModal{{$stock->item_number}}">
                                            <i class="fas fa-pills"></i>
                                         </button>

                                    {{--modal desgin--}}
                                    <div class="modal fade" id="searchrModal{{$stock->item_number}}" tabindex="-1" role="dialog" aria-labelledby="searchrModalLabel{{$stock->item_number}}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: green; color: white;">
                                                    <h5 class="modal-title" id="searchrModalLabel{{$stock->item_number}}">Enter UIN</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                
                                                <form method="POST" action="{{route('showdispenseform')}}">
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="container">
                                                            <div class="row mb-3">
                                                                <div class="col">
                                                                    <label for="uin">UIN</label>
                                                                    <input type="text" id="uin" name="uin" class="form-control" placeholder="Enter UIN">
                                                                </div>
                                                                <input value="{{$stock->item_name}}" id="item_name" name="item_name" hidden>
                                                                <input value="{{$stock->item_number}}" id="item_number" name="item_number" hidden>
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

                                </td>
                                  

                                
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


