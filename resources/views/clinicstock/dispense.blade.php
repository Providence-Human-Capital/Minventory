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
                    <form method="POST" action="" class="container">
                        <div class="row mb-3">
                            <div class="col" >
                                <label for="name">Name :{{ $employee->name }}</label><br>
                                <label for="name">UIN :{{ $employee->uin }}</label><br>
                                <label for="name">Date of Birth :{{ $employee->dob }}</label><br>
                                <label for="name">National ID :{{ $employee->national_id }}</label><br>
                                <label for="name">PhoneNumber
                                    :{{ $employee->country_code }}{{ $employee->phone_number }}</label><br>
                                <label for="name">Employeed at :{{ $employee->employeer }}</label><br>


                                <label for="checkbox">Are you a dependant? :</label>
                                <input type="checkbox" id="checkbox" name="checkbox" onclick="dependantFunction()"><br>
                            </div>
                            <div class="col" id ="dependant_info" style="display: none">
                                <div class="mb-3">
                                    <label for="name">Dependant 1 :{{ $employee->dependant1 }}</label><br>
                                    <label for="name">Dependant 1 ID :{{ $employee->dependant1_id }}</label><br>

                                </div>
                                <div class="mb-3">
                                    <label for="name">Dependant 2 :{{ $employee->dependant2 }}</label><br>
                                    <label for="name">Dependant2 ID :{{ $employee->dependant2_id }}</label><br>

                                </div>

                            </div>
                        </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <button class="btn btn-success" style="margin-top: 24px;">Dispense</button>
                                        </div>
                                    </div>
                    </form>



                </div>
            </div>
        </div>
    </div>
    <script>
        function dependantFunction() {
            var checkbox = document.getElementById("checkbox");
            var select = document.getElementById("dependant_info");
    
            if (checkbox.checked) {
                select.style.display = 'block';
            } else {
                select.style.display = 'none';
            }
        }
    </script>
    

</x-app-layout>
