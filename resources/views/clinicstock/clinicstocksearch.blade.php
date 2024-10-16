<x-app-layout>
    <x-slot name="header">
        <center>
            <div class="col-sm">
                <form action="{{ route('searchclinicstock') }}" method="GET">
                    <input type="text" name="isearch" id="isearch" value="{{ old('isearch') }}">
                    <button type="submit">Search</button>
                </form>
            </div>
        </center>

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


                        @foreach ($clinicstock as $stock)
                            <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $stock->item_name }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $stock->item_number }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $stock->item_quantity }}
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
