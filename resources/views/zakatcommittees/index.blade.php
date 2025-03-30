<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zakkat Committees') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
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
                    <form action="{{route('zakatcommittee.index')}}" method="GET" class="mt-3">
                        <div class="flex flex-row flex-w items-center">
                            <x-text-input id="search" class="mt-1 w-48 p-2 mx-2" type="text" name="search" value="{{ old('search') }}" autofocus autocomplete="cnic" />
                            <select name="search_type" id="search_type" class= "w-48 mt-1 border-gray-600 focus:ring-indigo-500  rounded-md shadow-sm" autofocus autocomplete="gender">
                                <option value="name" {{ old('search_type') == 'name' ? 'selected' : '' }}>Zakkat Committee</option>
                                <option value="id" {{ old('search_type') == 'id' ? 'selected' : '' }}>id</option> 
                                <option value="mna_id" {{ old('search_type') == 'mna_id' ? 'selected' : '' }}>MNA</option>
                                <option value="ac_id" {{ old('search_type') == 'ac_id' ? 'selected' : '' }}>Assistant Commissioner</option> 
                            </select>
                            <x-primary-button class="mt-1 ms-3" type="submit">
                                {{ __('Search') }}
                            </x-primary-button>
                            <a href="{{route('zakatcommittee.create')}}" class="py-1 bg-gray-800 border text-white mx-2 px-3 mt-1 rounded-md">New</a>
                            <a href="{{ route('zakatcommittee.export.csv', request()->query()) }}" class="py-1 bg-gray-800 border text-white mx-2 px-3 mt-1 rounded-md">
                                Export
                            </a>
                        </div>
                    </form>
                    
                </div>
                <div class="overflow-x-auto">
                    <table class="table-auto border-collapse border border-gray-400 w-full mt-2">
                        <thead>
                          <tr class="bg-gray-200">
                            <th class="border px-4 py-2">Id</th>
                            <th class="border px-4 py-2">Zakkat Committee</th>
                            <th class="border px-4 py-2">No of Beneficiaries</th>
                            <th class="border px-4 py-2">Currently Updated</th>
                            <th class="border px-4 py-2">Bank and Branch Name</th>
                            <th class="border px-4 py-2">Account Number</th>
                            <th class="border px-4 py-2">Assistant Commissioner</th>
                            <th class="border px-4 py-2">MNA</th>
                            <th class="border px-4 py-2">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($zakatcommittees as $zakatcommittee)
                                <tr>
                                    <td class="border px-4 py-2">{{$zakatcommittee->id}}</td>
                                    <td class="border px-4 py-2">{{$zakatcommittee->lzc_name}}</td>
                                    <td class="border px-4 py-2">{{$zakatcommittee->no_of_beneficiaries}}</td>
                                    <td class="border px-4 py-2">{{$zakatcommittee->beneficiaries_count}}</td>
                                    <td class="border px-4 py-2">{{$zakatcommittee->bank_name}}</td>
                                    <td class="border px-4 py-2">{{$zakatcommittee->acc_no}}</td>                                    
                                    <td class="border px-4 py-2">{{$zakatcommittee->asstcommissioners->name . ", AC(". $zakatcommittee->asstcommissioners->subdivisions->name.")"}}</td>
                                    <td class="border px-4 py-2">{{$zakatcommittee->mnas->name}}</td>
                                    <td class="border px-4 py-2"><a href="{{route('zakatcommittee.edit', $zakatcommittee->id)}}">Edit</a></td>
                                    
                                    {{-- <td>
                                        {{public_path('\\cards\\'. $employee->Name."_".$employee->id.".png")}}
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="back-width mx-3 mt-2 mb-2">
                    {{ $zakatcommittees->appends(request()->query())->links() }}
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
