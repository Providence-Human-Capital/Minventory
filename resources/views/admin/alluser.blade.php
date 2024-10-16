<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-sm">
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
                        <button type="submit">Search</button>
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
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table style="border-collapse: collapse;width: 100%;">
                        <tr style="padding: 8px;text-align: left;border-bottom: 1px solid #DDD;">
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
                            <div class="modal fade" id="resetModal{{ $users->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="resetModalLabel{{ $users->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="padding: 0px;height:50px">
                                            <div style="color:white;width:100%;height:100%;background-color:green;top:0px;text-align:center"
                                                class="modal-title" id="resetModalLabel{{ $users->id }}">
                                                <p style="padding-top:10px;display:inline">Password{{ $users->id }}</p>
                                                <button style="display:inline" type="button" class="close"
                                                    data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                        <form method="POST" action="/mainstock/{{ $users->id }}">
                                            <div style="padding-left:10px;padding-right:10px;width:100%">
                                                @csrf
                                                @method('patch')
                                                <p>Reset password for {{$users->name}}</p>
                                                <input type="text" id="password">
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
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="distributeModalLabel{{ $users->id }}">
                                                Delete User: {{$users->name}}</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form method="POST" action="{{route('deleteuser')}}">
                                            <div style="padding-left:10px;padding-right:10px;width:100%">
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
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
