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
                    <form method="POST" action="{{ route('beneficiary.store') }}">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li style="color:red;">{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div>
                            <x-input-label for="cnic" :value="__('CNIC')" />
                            <x-text-input id="cnic" class="block mt-1 w-full" type="text" name="cnic" :value="old('cnic')" min="13" max="13" required autofocus autocomplete="cnic" />
                            <x-input-error :messages="$errors->get('cnic')" class="mt-2" />
                        </div>
                
                        <div>
                            <x-input-label for="name" :value="__('name')" />
                            <x-text-input id="name" class="block mt-2 w-full" type="text" name="name" :value="old('name')" max="30" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="father_name" :value="__('Father Name')" />
                            <x-text-input id="father_name" class="block mt-2 w-full" type="text" name="father_name" :value="old('father_name')" max="30"  autofocus autocomplete="father_name" />
                            <x-input-error :messages="$errors->get('father_name')" class="mt-2" />
                        </div>

                        <div class="mt-2">
                            <x-input-label for="zc_id" :value="__('Zakkat Committee')" />
                            <select name="zc_id" id="zc_id" class="rounded w-full">
                                @foreach ($zakatcommittees as $zakatcommittee)
                                    <option value="{{$zakatcommittee->id}}">{{$zakatcommittee->lzc_name}}</option>    
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('zc_id')" class="mt-2" />
                        </div>

                        <div class="mt-2"> 
                            <x-input-label for="ac_id" :value="__('Assistant Commissioner')" />
                            <select name="ac_id" id="ac_id" class="rounded w-full">
                                @foreach ($asstcommissioners as $asstcommissioner)
                                    <option value="{{$asstcommissioner->id}}">{{$asstcommissioner->name. ", Assistant Commissioner(" .$asstcommissioner->subdivisions->name.")" }} </option>    
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('ac_id')" class="mt-2" />
                        </div>
                        <div class="mt-2">
                            <x-primary-button class="ms-3">
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                            
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('zc_id').addEventListener('change', function() {
            const zakatcommittee_data = @json($zakatcommittees); // Laravel data passed as JSON
            const ac_element = document.getElementById('ac_id'); // Target Assistant Commissioner select element
            
            // Loop through the data and update the selected option
            zakatcommittee_data.forEach(zakatcommittee => {
                if (zakatcommittee.id == this.value) {
                    ac_element.value = zakatcommittee.ac_id; // Set the matching value
                }
            });
        });
    </script>
</x-app-layout>
