<x-app-layout>
    <x-slot name="header">
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('pendingstock')" :active="request()->routeIs('pendingstock')">
                {{ __('Pending Stock') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('receivedstock')" :active="request()->routeIs('receivedstock')">
                {{ __('Received Stock') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline;float:right">
            <form action="{{ route('searchpstock') }}" method="GET">
                <input type="text" name="ssearch" id="ssearch" value="{{ old('ssearch') }}">
                <button type="submit">Search</button>
            </form>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Pending stocks") }}
                    @if ($search->isEmpty())
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
                                @foreach ($search as $pstock)
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
        
        </div>
    </div>
</x-app-layout>