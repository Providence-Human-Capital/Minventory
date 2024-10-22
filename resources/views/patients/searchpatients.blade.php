<x-app-layout>
    <x-slot name="header">
        <center>
            <button class="btn btn-primary mb-3" onclick="printResults()">
                <i class="fa fa-print"></i> Print Results
            </button>
        </center>
    </x-slot>

    <div class="py-12">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div id="printArea" class="print-area">
                        <form method="POST" action="{{ route('savedispense') }}" class="container">
                            @csrf
    
                            @if($employee)
                                <div class="row mb-3">
                                    <label for="UIN">UIN:</label>
                                    <input type="text" name="UIN" id="UIN" value="{{ $employee->uin }}" style="border: none" readonly>
                                </div>
    
                                <div class="row mb-3">
                                    <div class="col">
                                        <label>Name: {{ $employee->name }}</label><br>
                                        <label>Date of Birth: {{ $employee->dob }}</label><br>
                                        <label>National ID: {{ $employee->national_id }}</label><br>
                                        <label>Phone Number: {{ $employee->country_code }} {{ $employee->phone_number }}</label><br>
                                        <label>Employed at: {{ $employee->employeer }}</label><br>
    
                                    </div>
                                    <div class="col" id="dependant_info">
                                        <div class="mb-3">
                                            <label>Dependant 1: {{ $employee->dependant1 }}</label><br>
                                            <label>Dependant 1 ID: {{ $employee->dependant1_id }}</label><br>
                                        </div>
                                        <div class="mb-3">
                                            <label>Dependant 2: {{ $employee->dependant2 }}</label><br>
                                            <label>Dependant 2 ID: {{ $employee->dependant2_id }}</label><br>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="row m-3">
                                    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                                        <thead>
                                            <tr style="background-color: #f2f2f2;">
                                                <th style="padding: 8px; border: 1px solid #ddd;">DRUG</th>
                                                <th style="padding: 8px; border: 1px solid #ddd;">RECIPIENT</th>
                                                <th style="padding: 8px; border: 1px solid #ddd;">DISPENSER</th>
                                                <th style="padding: 8px; border: 1px solid #ddd;">DISPENSE TIME</th>
                                                <th style="padding: 8px; border: 1px solid #ddd;">CLINIC</th>
                                                <th style="padding: 8px; border: 1px solid #ddd;">AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($history as $historys)
                                                <tr>
                                                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $historys->drug }}</td>
                                                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $historys->recipient }}</td>
                                                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $historys->dispenser }}</td>
                                                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $historys->dispense_time }}</td>
                                                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $historys->clinic }}</td>
                                                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $historys->damount }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning">This UIN doesn't exist.</div>
                            @endif
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <script>
        function printResults() {
            var printContents = document.getElementById('printArea').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</x-app-layout>
