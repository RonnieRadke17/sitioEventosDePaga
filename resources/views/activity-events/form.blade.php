@extends('layouts.app')    
@section('content')
    <div id="container">

        {{-- falta poner la configuración del form bien --}}
    {{-- mostramos los errores  --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>   
    @endif


        {{-- <form method="POST" action="{{ $activitiesEvent != null ? route('activity-events.update', $id) : route('activity-events.store') }}">
            @csrf
            @if($activitiesEvent != null) 
                    @method('PATCH') 
            @else
            <input type="hidden" name="event_id" value="{{ $id }}">
            
            @endif
            
            <div id="step-2">
                <ul class="grid w-full gap-1 md:grid-cols-1 max-h-48 overflow-y-auto" id="list-activities">
                    @foreach($activities as $activity)
                    <li>
                        <button type="button" onclick="toggleAccordion(this)" class="inline-flex items-center justify-between w-full p-1 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-100 dark:border-gray-700 hover:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <div class="flex items-center p-1">
                                <input type="checkbox" name="selected_activities[]" value="{{ $activity->id }}" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <div class="w-full text-lg font-semibold ml-2">{{ $activity->name }}</div>
                            </div>
                        </button>
                        
                        <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-100 border-b">
                                        <th class="py-3 px-4 text-left dark:text-white dark:bg-gray-600 dark:border-gray-600">
                                            M
                                        </th>
                                        <th class="py-3 px-4 text-left dark:text-white dark:bg-gray-600 dark:border-gray-600">
                                            F
                                        </th>
                                        <th class="py-3 px-4 text-left dark:text-white dark:bg-gray-600 dark:border-gray-600">
                                            Mix
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subs as $sub)
                                    <tr class="border-b cursor-pointer activity-row">
                                        <td class="py-2 px-4">
                                            <input type="checkbox" name="genders[{{ $activity->id }}][M][{{ $sub->id }}]" value="{{ $sub->id }}">{{ $sub->name }}
                                        </td>
                                        <td class="py-2 px-4">
                                            <input type="checkbox" name="genders[{{ $activity->id }}][F][{{ $sub->id }}]" value="{{ $sub->id }}">{{ $sub->name }}
                                        </td>
                                        
                                        <td class="py-2 px-4">
                                            <input type="checkbox" name="genders[{{ $activity->id }}][Mix][{{ $sub->id }}]" value="{{ $sub->id }}">{{ $sub->name }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @error('selected_activities')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror          
            </div>


            <div class="flex justify-center mt-4">
                <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{ $activitiesEvent != null ? 'Actualizar' : 'Crear' }}</button>
            </div>
        </form> --}}

        <form method="POST" action="{{ $activitiesEvent != null ? route('activity-events.update', $id) : route('activity-events.store') }}">
    @csrf
        

    @if($activitiesEvent != null)
        @method('PATCH')
    @else
        <input type="hidden" name="event_id" value="{{ $id }}">
    @endif

    <div id="step-2">
        <ul class="grid w-full gap-1 md:grid-cols-1 max-h-48 overflow-y-auto" id="list-activities">
            @foreach($activities as $activity)
                @php
                    $selectedRows = $selectedGenders[$activity->id] ?? collect();
                @endphp
                <li>
                    <button type="button" onclick="toggleAccordion(this)"
                        class="inline-flex items-center justify-between w-full p-1 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-100 dark:border-gray-700 hover:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <div class="flex items-center p-1">
                            <input type="checkbox"
                                name="selected_activities[]"
                                value="{{ $activity->id }}"
                                class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                {{ $activitiesEvent && in_array($activity->id, $selectedActivities ?? []) ? 'checked' : '' }}>
                            <div class="w-full text-lg font-semibold ml-2">{{ $activity->name }}</div>
                        </div>
                    </button>

                    <!-- Contenido del acordeón -->
                    <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="py-3 px-4 text-left dark:text-white dark:bg-gray-600 dark:border-gray-600">M</th>
                                    <th class="py-3 px-4 text-left dark:text-white dark:bg-gray-600 dark:border-gray-600">F</th>
                                    <th class="py-3 px-4 text-left dark:text-white dark:bg-gray-600 dark:border-gray-600">Mix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subs as $sub)
                                    <tr class="border-b cursor-pointer activity-row">
                                        @foreach(['M', 'F', 'Mix'] as $gender)
                                            @php
                                                $isChecked = $selectedRows->contains(function ($row) use ($sub, $gender) {
                                                    return $row->sub_id == $sub->id && $row->gender === $gender;
                                                });
                                            @endphp
                                            <td class="py-2 px-4">
                                                <input
                                                    type="checkbox"
                                                    name="genders[{{ $activity->id }}][{{ $gender }}][{{ $sub->id }}]"
                                                    value="{{ $sub->id }}"
                                                    {{ $activitiesEvent && $isChecked ? 'checked' : '' }}>
                                                {{ $sub->name }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </li>
            @endforeach
        </ul>

        @error('selected_activities')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="flex justify-center mt-4">
        <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">
            {{ $activitiesEvent != null ? 'Actualizar' : 'Crear' }}
        </button>
    </div>
</form>


    </div>


    <script>
        function toggleAccordion(button) {
            // Encuentra el contenido del acordeón dentro del mismo <li>
            const content = button.nextElementSibling;
        
            // Alterna la visibilidad del contenido
            if (content.classList.contains('max-h-0')) {
                content.classList.remove('max-h-0'); // Expande el contenido
                content.classList.add('max-h-[150px]'); // Ajusta el valor de max-h según tus necesidades
                content.classList.remove('overflow-hidden');
                content.classList.add('overflow-y-auto'); // Habilita el desplazamiento si el contenido es mayor que el tamaño máximo
            } else {
                content.classList.remove('max-h-[150px]'); // Contrae el contenido
                content.classList.add('max-h-0');
                content.classList.remove('overflow-y-auto');
                content.classList.add('overflow-hidden'); // Oculta el contenido cuando está contraído
            }
        }
    </script>
    

@endsection    
    
        
 
