<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li style="color:red;">{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="flex flex-row flex-wrap items-center mx-1">
                    <form action="{{route('beneficiary.index')}}" method="GET" class="mt-3">
                        <div class="flex flex-row flex-w items-center">
                            <x-text-input id="search" class="mt-1 w-48 p-2 mx-2" type="text" name="search" value="{{ old('search') }}" autofocus autocomplete="cnic" />
                            <select name="search_type" id="search_type" class= "w-48 mt-1 border-gray-600 focus:ring-indigo-500  rounded-md shadow-sm" autofocus autocomplete="gender">
                                <option value="cnic" {{ old('search_type') == 'cnic' ? 'selected' : '' }}>CNIC </option> 
                                <option value="name" {{ old('search_type') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="id" {{ old('search_type') == 'id' ? 'selected' : '' }}>id</option> 
                            </select>
                            <x-primary-button class="mt-1 ms-3" type="submit">
                                {{ __('Search') }}
                            </x-primary-button>
                            <a href="{{route('beneficiary.create')}}" class="px-3"><i class="fas fa-user-plus fa-lg"></i></a>        
                        </div>
                    </form>
                    
                </div>
                
                <table class="table-auto border-collapse border border-gray-400 w-full mt-2">
                    <thead>
                      <tr class="bg-gray-200">
                        <th class="border px-4 py-2">Id</th>
                        <th class="border px-4 py-2">CNIC</th>
                        <th class="border px-4 py-2">Beneficiary Name</th>
                        <th class="border px-4 py-2">Father Name</th>
                        <th class="border px-4 py-2">Zakkat Committee</th>
                        <th class="border px-4 py-2">Assistant Commissioner</th>
                        <th class="border px-4 py-2">User</th>
                        <th class="border px-4 py-2">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($beneficiaries as $beneficiary)
                            <tr>
                                <td class="border px-4 py-2">{{$beneficiary->id}}</td>
                                <td class="border px-4 py-2">{{$beneficiary->cnic}}</td>
                                <td class="border px-4 py-2">{{$beneficiary->name}}</td>
                                <td class="border px-4 py-2">{{$beneficiary->father_name}}</td>
                                <td class="border px-4 py-2">{{$beneficiary->zakatcommittees->lzc_name}}</td>
                                <td class="border px-4 py-2">{{$beneficiary->asstcommissioners->name . ", AC(". $beneficiary->asstcommissioners->subdivisions->name.")"}}</td>
                                <td class="border px-4 py-2">{{$beneficiary->users->name}}</td>
                                <td class="border px-4 py-2"><a href="{{route('beneficiary.edit', $beneficiary->id)}}">Edit</a></td>
                                
                                {{-- <td>
                                    {{public_path('\\cards\\'. $employee->Name."_".$employee->id.".png")}}
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="back-width mx-3 mt-2 mb-2">
                    {{ $beneficiaries->links() }}
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
