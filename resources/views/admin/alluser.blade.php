<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" style="display:inline">
                    <x-nav-link :href="route('getuseroptions')" :active="request()->routeIs('getuseroptions')">
                        {{ __('Users') }}
                    </x-nav-link>
                    <x-nav-link :href="route('registerationform')" :active="request()->routeIs('registerationform')">
                        {{ __('Create Users') }}
                    </x-nav-link>
                </div>
                <div class="col-sm">
                </div>
                <div class="col-sm">
                    <form action="{{ route('searchmainstock') }}" method="GET">
                        <input type="text" name="isearch" id="isearch" value="{{ old('isearch') }}">
                        <button type="submit" class="btn btn-success">Search</button>
                    </form>
                </div>
            </div>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 dark:bg-gray-800">
                    <table style="border-collapse: collapse;width: 100%;" class="dark:bg-gray-800 dark:text-gray-200">
                        <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;" class="bg-gray-400 dark:bg-zinc-900 dark:text-white text-black">
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Name</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Email</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Role</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Clinic</th>
                            <th style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">Action</th>
                        </tr>


                        @foreach ($user as $users)
                            <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $users->name }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $users->email }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $users->Role }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{ $users->clinic }}
                                </td>
                                <td style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
                                    {{-- modal button to reset --}}
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#resetModal{{ $users->id }}">
                                           <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                    {{-- modal button to distribute --}}
                                    <div style="display:inline">

                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#deleteModal{{ $users->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- reset model design start here --}}
                            <div class="modal fade " id="resetModal{{ $users->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="resetModalLabel{{ $users->id }}" aria-hidden="true">
                                <div class="modal-dialog " role="document">
                                    <div class="modal-content dark:bg-emerald-950 bg-green-200 dark:text-white">
                                        <div class="modal-header" style="padding: 0px;height:50px">
                                            <div style="color:white;width:100%;height:100%;background-color:green;top:0px;text-align:center"
                                                class="modal-header " id="resetModalLabel{{ $users->id }}">
                                                <p class=" ">Password{{ $users->id }}</p>
                                                <button style="display:inline" type="button" class="close dark:text-white"
                                                    data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                        <form method="POST" action="/mainstock/{{ $users->id }}">
                                            <div style="padding-left:10px;padding-right:10px;width:100%" class="mt-3">
                                                @csrf
                                                @method('patch')
                                                <p>Reset password for {{$users->name}}</p>
                                                <input type="text" id="password" class="dark:bg-gray-700">
                                                <input type="submit"
                                                    style="background-color: green;color:white;size:10pt;padding:5pt;margin:15pt;border-radius:5px;border-style:outset;border-color:black"
                                                    value="Reset">
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>

                            {{-- delete model design start here --}}
                            <div class="modal fade" id="deleteModal{{ $users->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="deleteModalLabel{{ $users->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content dark:bg-red-900 bg-red-200 dark:text-white">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="distributeModalLabel{{ $users->id }}">
                                                Delete User: {{$users->name}}</h5>
                                            <button type="button" class="close dark:text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form method="POST" action="{{route('deleteuser')}}">
                                            <div style="padding-left:10px;padding-right:10px;width:100%" class="mt-2">
                                                @csrf
                                                <p>Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                                            </div>
                                            <input type="hidden" name="userid" value="{{ $users->id }}">
                                                <input type="submit" class="btn btn-danger"
                                                    style="color:white;size:10pt;padding:5pt;margin:15pt;border-radius:5px;border-style:outset;border-color:black"
                                                    value="Delete user">
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                           
                        @endforeach
                    </table>
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#resetModal{{ $users->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                </div>
                {{-- modal button to delete --}}
                <div style="display:inline">

                    <button type="button" class="btn btn-danger" data-toggle="modal"
                        data-target="#deleteModal{{ $users->id }}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                </td>
                </tr>

                {{-- delete model design start here --}}
                <div class="modal fade" id="deleteModal{{ $users->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="deleteModalLabel{{ $users->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="distributeModalLabel{{ $users->id }}">
                                    Delete User: {{ $users->name }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form method="POST" action="{{ route('deleteuser') }}">
                                <div style="padding-left:10px;padding-right:10px;width:100%">
                                    @csrf
                                    <p>Once your account is deleted, all of its resources and data will be permanently
                                        deleted. Before deleting your account, please download any data or information
                                        that you wish to retain.</p>
                                </div>
                                <input type="hidden" name="userid" value="{{ $users->id }}">
                                <input type="submit" class="btn btn-danger"
                                    style="color:white;size:10pt;padding:5pt;margin:15pt;border-radius:5px;border-style:outset;border-color:black"
                                    value="Delete user">
                        </div>
                        </form>

                    </div>
                </div>
                {{-- reset model design start here --}}
                <div class="modal fade" id="resetModal{{ $users->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="resetModalLabel{{ $users->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="padding: 0px;height:50px">
                                <div style="color:white;width:100%;height:100%;background-color:green;top:0px;text-align:center"
                                    class="modal-title" id="resetModalLabel{{ $users->id }}">
                                    <p style="padding-top:10px;display:inline">Password{{ $users->id }}</p>
                                    <button style="display:inline" type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <form method="POST" action="/admin/user/reset/{{ $users->id }}">
                                <div style="padding-left:10px;padding-right:10px;width:100%">
                                    @csrf
                                    @method('patch')
                                    <p>
                                        <label for="password{{ $users->id }}">Reset password for : {{ $users->name }}</label>
                                        <input type="password" name="password" id="password{{ $users->id }}" />
                                        <i class="bi bi-eye-slash" id="togglePassword{{ $users->id }}"></i>
                                        <input hidden value="{{$users->id}}" id="id" name="id">
                                    </p>

                                    <input type="submit"
                                        style="background-color: green;color:white;size:10pt;padding:5pt;margin:15pt;border-radius:5px;border-style:outset;border-color:black"
                                        value="Reset">
                                </div>
                            </form>


                        </div>
                    </div>
                </div>

                
            </div>
            @endforeach
            </table>
        </div>
    </div>
    </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const togglePassword = document.querySelectorAll("[id^='togglePassword']");
        
        togglePassword.forEach(toggle => {
            toggle.addEventListener("click", function () {
                const userId = this.id.replace('togglePassword', '');
                const passwordField = document.getElementById('password' + userId);

                // Toggle the type attribute
                const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
                passwordField.setAttribute("type", type);
                
                // Toggle the icon
                this.classList.toggle("bi-eye");
                this.classList.toggle("bi-eye-slash");
            });
        });
    });
    </script>

</x-app-layout>
