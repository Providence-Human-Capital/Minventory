<x-app-layout>
    <x-slot name="header">
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('showrequests')" :active="request()->routeIs('showrequests')">
                {{ __('Pending Requests') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('showarequests')" :active="request()->routeIs('showarequests')">
                {{ __('Approved Requests') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('showdrequests')" :active="request()->routeIs('showdrequests')">
                {{ __('Denied Requests') }}
            </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
            <x-nav-link :href="route('showallrequests')" :active="request()->routeIs('showallrequests')">
                {{ __('Search Requests') }}
            </x-nav-link>
        </div>
        
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:bg-gray-800">
                    <table class="dark:bg-gray-800 dark:text-gray-200 table table-striped table-bordered">
<thead>
                        <tr class="bg-gray-400 dark:bg-zinc-900 dark:text-white text-black">
                            <th>Item
                                Name</th>
                            <th>Item
                                Number
                            </th>
                            <th>
                                Quantity</th>
                            <th>Clinic</th>
                            <th>Status
                            </th>
                            <th>
                                Requester</th>
                            <th>
                                Requested At:
                            </th>
                            <th>
                                View
                            </th>
                        </tr>
                        </thead>

                        @foreach ($requests as $request)
                        <tr class="dark:bg-gray-700 bg-gray-300">
                            <th>{{$request->item_name}}</th>
                            <th>{{$request->item_number}}</th>
                            <th>{{$request->item_quantity}}</th>
                            <th>{{$request->clinic}}</th>
                            <th>{{$request->status}}</th>
                            <th>{{$request->requester}}</th>
                            <th>{{$request->created_at}}</th>
                            <th>
                                <form action="{{ route('viewrequest') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $request->id }}">
                                    <button type="submit" class="btn" style="background: none; border: none; padding: 0; cursor: pointer;">
                                        <i class="fas fa-eye" style="color: blue; font-size: 24px;" title="Approve"></i>
                                    </button>
                                </form> 

                            </th>
                        </tr>



                        @endforeach
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>