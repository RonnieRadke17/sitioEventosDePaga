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
                <ul class="grid w-full gap-1 md:grid-cols-1">
                    <li>    
                        <label for="react-option" class="inline-flex items-center justify-between w-full p-1 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 hover:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">                           
                            <div class="flex items-center p-1">
                                <input checked id="red-checkbox" type="checkbox" value="" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <div class="w-full text-lg font-semibold ml-2">React Js</div>
                            </div>
                            <div class="flex space-x-2">
                                <div>
                                    <input type="checkbox" id="op1" value="" class="hidden peer">
                                    <label for="op1" class="inline-flex items-center justify-between w-full p-1 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 dark:peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">                           
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">M</div>
                                        </div>
                                    </label>
                                </div>
                                <div>
                                    <input type="checkbox" id="op2" value="" class="hidden peer">
                                    <label for="op2" class="inline-flex items-center justify-between w-full p-1 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 dark:peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">                           
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">M</div>
                                        </div>
                                    </label>
                                </div>
                                <div>
                                    <input type="checkbox" id="op3" value="" class="hidden peer">
                                    <label for="op3" class="inline-flex items-center justify-between w-full p-1 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 dark:peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">                           
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">M</div>
                                        </div>
                                    </label>
                                </div>

                            </div>
                        </label>
                    </li>
                    
                    
                </ul>
                
                
                
                
            </div>

        
            <div class="flex justify-center mt-4">
                {{-- <button type="button" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2" id="to-step-2">Next</button> --}}
                <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
            </div>
    </div>
 
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