<x-app-layout>
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
    <center>
        <div style="margin: 50px;">
            <form method="POST" action="{{ route('createclinic') }}" style="background-color: #d4edda; border-radius: 15px; padding: 30px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 40%;">
                @csrf
                <h2 style="text-align: center; color: #343a40; margin-bottom: 20px;">Create a New Clinic</h2>

                <!-- Clinic Name Input -->
                <label for="clinic_name" style="font-size: 18px; color: #343a40;">New Clinic Name</label><br>
                <input type="text" name="clinic_name" id="clinic_name" style="width: 100%; padding: 10px; font-size: 16px; border-radius: 8px; border: 1px solid #ced4da; margin-bottom: 20px;" required><br>

                <!-- Database Name Input with Icon inside -->
                <label for="database_name" style="font-size: 18px; color: #343a40;">Clinic Database Name</label><br>
                <div style="position: relative; width: 100%; display: inline-block;">
                    <input type="text" name="database_name" id="database_name" readonly style="width: 100%; padding: 10px 40px 10px 10px; font-size: 16px; border-radius: 8px; border: 1px solid #ced4da; margin-bottom: 20px; display: inline-block;" required>
                    <i id="copyButton" class="fas fa-copy" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer; font-size: 24px; color: #007bff;" title="Copy to clipboard"></i>
                </div><br>

                <p style="color: #495057; font-size: 16px; margin-top: 10px;">Copy the above database name and use it as the name for the table.</p><br>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success" style="width: 100%; padding: 12px; font-size: 18px; background-color: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer;">Create Clinic</button>
            </form>
        </div>
    </center>

    <!-- Font Awesome for copy icon -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Include FontAwesome Icons -->

    <script>
        $(document).ready(function() {
            $('#clinic_name').on('input', function() {
                const clinicname = $('#clinic_name').val(); // Get the value of the input field
                const tableName = clinicname.replace(/[^a-zA-Z0-9]/g, ''); // Remove non-alphanumeric characters
                $('#database_name').val(tableName.toLowerCase() + '_stocks'); // Set the generated table name to the other input
            });

            // Add event listener for the copy button
            $('#copyButton').on('click', function() {
                const copyText = document.getElementById('database_name'); // Get the text field

                // Use Clipboard API for copying (modern approach)
                navigator.clipboard.writeText(copyText.value).then(function() {
                    // Optional: Show an alert or a success message
                    alert('Database name copied: ' + copyText.value);
                }).catch(function(err) {
                    // In case of error
                    console.error('Error copying text: ', err);
                    alert('Failed to copy the database name.');
                });
            });
        });
    </script>
</x-app-layout>
