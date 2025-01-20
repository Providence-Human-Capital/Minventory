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

                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">                                  
                                         <form method="POST" action="{{route('showdispenseform')}}">
                                                @csrf
                                                        <input value="{{$stock->item_name}}" id="item_name" name="item_name" hidden>
                                                        <input value="{{$stock->item_number}}" id="item_number" name="item_number" hidden>
    
                                                <button type="submit" class="btn btn-success" >
                                                    <i class="fas fa-pills"></i>
                                                 </button>
                                            
                                        </form>

                                </td>
                                  

                                
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


