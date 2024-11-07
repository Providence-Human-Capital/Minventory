<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="py-12">
                        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-3 text-gray-900 dark:text-gray-100">
                                    <form method="POST" action="{{route('showpatients')}}">
                                        <div class="modal-body">
                                            @csrf
                                            <div class="container">
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="uin">UIN</label>
                                                        <input type="text" id="uin" name="uin" class="form-control" placeholder="Enter UIN">
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary" style="width: 100%;">Search</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>

    
</x-app-layout>
