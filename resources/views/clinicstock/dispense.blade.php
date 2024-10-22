<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{route('savedispense')}}" class="container">
                        @csrf

                        @if($employee)
                        <div class="row mb-3">
                            UIN:<input type="text" name="UIN" id="UIN"value="{{ $employee->uin }}" style="border: none" readonly></input>
                        </div>
                           
                        <div class="row mb-3">
                            <div class="col">
                                <label>Name: {{ $employee->name }}</label><br>
                                <label>Date of Birth: {{ $employee->dob }}</label><br>
                                <label>National ID: {{ $employee->national_id }}</label><br>
                                <label>Phone Number: {{ $employee->country_code }} {{ $employee->phone_number }}</label><br>
                                <label>Employed at: {{ $employee->employeer }}</label><br>
                    
                                <label for="checkbox">Are you a dependant?:</label>
                                <input type="checkbox" id="checkbox" name="checkbox" onclick="dependantFunction()"><br>
                            </div>
                            <div class="col" id="dependant_info" style="display: none;">
                                <div class="mb-3">
                                    <label>Dependant 1: {{ $employee->dependant1 }}</label><br>
                                    <label>Dependant 1 ID: {{ $employee->dependant1_id }}</label><br>
                                    <input type="checkbox" id="dependant1" name="dependant1" ><br>
                                </div>
                                <div class="mb-3">
                                    <label>Dependant 2: {{ $employee->dependant2 }}</label><br>
                                    <label>Dependant 2 ID: {{ $employee->dependant2_id }}</label><br>
                                    <input type="checkbox" id="dependant2" name="dependant2" ><br>
                                </div>
                            </div>
                        </div>
                       
                        </div>
                        <div class="row m-3">
                            <div class="col-4" >
                                <label for="drug">Drug Being Dispensed</label>
                                <input type="text" name="drug" id="drug" value="{{ $drug }}" readonly class="form-control">
                                <input type="text" name="drug_number" id="drug_number" value="{{ $drug_number}}" readonly hidden class="form-control">
                            </div>
                            <div class="col-8">
                                <label for="amount">Amount Being Dispensed</label>
                                <input type="number" name="damount" id="damount" class="form-control" >
                            </div>
                        </div>
                        
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <button class="btn btn-success" style="margin-top: 24px;">Dispense</button>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">This UIN doesn't exist.</div>
                    @endif
                    
                    </form>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
