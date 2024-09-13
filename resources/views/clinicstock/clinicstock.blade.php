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
                    <table style="border-collapse: collapse;width: 100%;">
                        <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Item Name</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Item Number</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Quantity</th>
                        </tr>


                        @foreach ($avenue81 as $avenue81s)
                        <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                            <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                {{$avenue81s->item_name}}
                            </td>
                            <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                {{$avenue81s->item_number}}
                            </td>
                            <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                {{$avenue81s->item_quantity}}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>