    <div id="container">

            <h2 class="text-3xl font-bold text-center text-orange-600 mb-2">{{$mode}} actividades</h2>
            <div id="step-2">
                
                <div class="mb-4" >
                    <label for="is_with_activities" class="block text-gray-700 dark:text-white">Actividades</label>
                    <select name="is_with_activities" id="is_with_activities" class="w-full px-4 py-2 border rounded-lg dark:text-white dark:bg-gray-600 dark:border-gray-600">
                        <option value="0" {{ old('is_with_activities', isset($event) ? $event->activities : '') == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('is_with_activities', isset($event) ? $event->activities : '') == 1 ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>     
                
                {{-- tabla acts --}}
                {{-- <ul class="grid w-full gap-1 md:grid-cols-1 max-h-48 overflow-y-auto">
                    @foreach($activities as $activity)
                    <li>
                        <button type="button" for="react-option" class="inline-flex items-center justify-between w-full p-1 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-100 dark:border-gray-700 hover:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">                           
                            <div class="flex items-center p-1">
                                <input id="red-checkbox" type="checkbox" name="selected_activities[]" value="{{ $activity->id }}" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <div class="w-full text-lg font-semibold ml-2">{{ $activity->name }}</div>
                            </div>
                        </button>
                    </li>
                    @endforeach
                </ul> --}}
                <ul class="grid w-full gap-1 md:grid-cols-1 max-h-48 overflow-y-auto">
                    @foreach($activities as $activity)
                    <li>
                        <button type="button" onclick="toggleAccordion(this)" class="inline-flex items-center justify-between w-full p-1 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-100 dark:border-gray-700 hover:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <div class="flex items-center p-1">
                                <input id="red-checkbox-{{ $activity->id }}" type="checkbox" name="selected_activities[]" value="{{ $activity->id }}" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
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
                                    <tr class="border-b hover:bg-gray-50 cursor-pointer activity-row" data-activity-id="{{ $activity->id }}">
                                        <td class="py-2 px-4">{{ $sub->name }}</td>
                                        <td class="py-2 px-4">{{ $sub->name }}</td>
                                        <td class="py-2 px-4">{{ $sub->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </li>
                    @endforeach
                </ul>
                                
            </div>
            <div class="flex justify-center mt-4">
                {{-- <button type="button" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2" id="to-step-2">Next</button> --}}
                <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
            </div>
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
    
    
 
{{-- <table class="min-w-full bg-white border border-gray-200 dark:text-white dark:bg-gray-600 dark:border-gray-600 hidden" id="activity_table">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="py-2 px-4 text-left dark:text-white dark:bg-gray-600 dark:border-gray-600">Nombre</th>
                            <th class="py-2 px-4 text-left dark:text-white dark:bg-gray-600 dark:border-gray-600">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        <tr class="border-b hover:bg-gray-50 cursor-pointer activity-row" data-activity-id="{{ $activity->id }}">
                            <td class="py-2 px-4">{{ $activity->name }}</td>
                            <td class="py-2 px-4 text-center">
                                <input type="checkbox" name="selected_activities[]" value="{{ $activity->id }}"
                                {{ (isset($eventActivities[$activity->id]) || in_array($activity->id, old('selected_activities', []))) ? 'checked' : '' }}>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table> --}}