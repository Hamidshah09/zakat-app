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
                    <table class="table-auto border-collapse border border-gray-400 w-full mt-2">
                        <thead>
                          <tr class="bg-gray-200">
                            <th class="border px-4 py-2 w-1/2">Assistant Commissioner</th>
                            <th class="border px-4 py-2 w-1/2">No of Beneficiaries</th>
                          </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp

                            @foreach ($acs as $ac)
                                <tr>
                                    <td class="border px-4 py-2 w-1/2">{{ "Assistant Commisioner (" .$ac->subdivisions->name . ")"}}</td>
                                    <td class="border px-4 py-2 w-1/2">{{$ac->beneficiaries_count}}</td>
                                    @php
                                            $total = $total + $ac->beneficiaries_count;
                                    @endphp
                                    
                                    {{-- <td>
                                        {{public_path('\\cards\\'. $employee->Name."_".$employee->id.".png")}}
                                    </td> --}}
                                </tr>
                            @endforeach
                            <tr>
                                <td class="border px-4 py-2 font-bold w-1/2">Total</td>
                                <td class="border px-4 py-2 font-bold w-1/2">{{$total}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
