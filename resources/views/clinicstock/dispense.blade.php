<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('savedispense') }}" class="container">
                        @csrf
                </div>
                <div class="row m-3">
                    <div class="col-4">     
                        <label for="drug">Drug Being Dispensed</label>
                        <input type="text" name="drug" id="drug" value="{{ $drug }}" readonly
                            class="form-control">
                        <input type="text" name="drug_number" id="drug_number" value="{{ $drug_number }}" readonly
                            hidden class="form-control">
                    </div>
                    <div class="col-8">
                        <label for="amount">Amount Being Dispensed</label>
                        <input type="number" name="damount" id="damount" class="form-control">
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
            const dependantInfo = document.getElementById('dependant_info');
            const checkbox = document.getElementById('checkbox');

            // Toggle the display property based on the checkbox state
            if (checkbox.checked) {
                dependantInfo.style.display = 'block';
            } else {
                dependantInfo.style.display = 'none';
            }

        }
    </script>
</x-app-layout>
