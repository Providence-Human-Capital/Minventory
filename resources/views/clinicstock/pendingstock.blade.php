<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pendin Stocks') }}
        </h2>
        
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
                    {{ __("Pending stocks") }}
                    @if ($pstocks->isEmpty())
                        <p>No pending request</p>
                    @else
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
                                    Action
                                </th>
                            </tr>
                            <tr>
                                @foreach ($pstocks as $pstock)
                                <th>{{$pstock->item_name}}</th>
                                <th>{{$pstock->item_number}}</th>
                                <th>{{$pstock->item_quantity}}</th>
                                <th>{{$pstock->clinic}}</th>
                                <th>{{$pstock->status}}</th>
                                <th>{{$pstock->procurer}}</th>
                                <th>{{$pstock->created_at}}</th>
                                <th>
                                    <form action="{{ route('changestatus') }}" method="POST">
                                        @method('PATCH')
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $pstock->id }}">
                                        <input type="submit" class="btn btn-success " value="Received" style="margin: 6pt">
                                    </form>
                                </th>
                            </tr>



                            @endforeach
                            </tr>
                        </table>
                    </center>
                    @endif       
                </div>

                
            </div>
            
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
</x-app-layout>