            <!-- Paso 1: Event Details -->
        <div id="step-1">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Event Name</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea name="description" id="description" class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>
                <div class="flex mb-4">
                    <div class="w-1/2 pr-2">
                        <label for="event_date" class="block text-gray-700">Event Date</label>
                        <input type="text" name="event_date" id="event_date" class="w-full px-4 py-2 border rounded-lg" placeholder="YYYY-MM-DD HH:MM">
                    </div>
                    <div class="w-1/2 pl-2">
                        <label for="registration_deadline" class="block text-gray-700">Registration Deadline</label>
                        <input type="text" name="registration_deadline" id="registration_deadline" class="w-full px-4 py-2 border rounded-lg" placeholder="YYYY-MM-DD HH:MM">
                    </div>
                </div>

                <div class="flex mb-4">
                    <div class="w-1/2 pr-2">
                        <label for="kit_delivery" class="block text-gray-700">Kit Delivery Date (Optional)</label>
                        <input type="text" name="kit_delivery" id="kit_delivery" class="w-full px-4 py-2 border rounded-lg" placeholder="YYYY-MM-DD HH:MM">
                    </div>
                    <div class="w-1/2 pl-2">
                        <label for="is_limited_capacity" class="block text-gray-700">Limited Capacity</label>
                        <select name="is_limited_capacity" id="is_limited_capacity" class="w-full px-4 py-2 border rounded-lg">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4 hidden" id="capacity-field">
                    <label for="capacity" class="block text-gray-700">Capacity</label>
                    <input type="number" name="capacity" id="capacity" class="w-full px-4 py-2 border rounded-lg" min="1">
                </div>
                
                <div class="mb-4">
                    <label for="price" class="block text-gray-700">Price</label>
                    <input type="number" name="price" id="price" class="w-full px-4 py-2 border rounded-lg" step="0.01" min="0">
                </div>
                @if ($mode == 'Editar')
                <div class="mb-4">
                    <label for="status" class="block text-gray-700">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border rounded-lg">
                        <option value="Activo">Active</option>
                        <option value="Inactivo">Inactive</option>
                        <option value="Cancelado">Cancelled</option>
                    </select>
                </div>
                @endif

                <div class="flex justify-center mt-4">
                    <button type="button" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2" id="to-step-2">Next</button>
                </div>
                
        </div>

            <!-- Paso 2: Activities -->
        <div id="step-2" class="hidden">
            {{-- aqui se muestra el boton que muestra las actividades --}}
            <div class="mb-4">
                <label for="is_with_activities" class="block text-gray-700">Actividades</label>
                <select name="is_with_activities" id="is_with_activities" class="w-full px-4 py-2 border rounded-lg">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            
            <table class="min-w-full bg-white border border-gray-200 hidden" id="activity_table">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="py-2 px-4 text-left">Categoría</th>
                        <th class="py-2 px-4 text-left">Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activityCategories as $category)
                        <tr class="bg-gray-200">
                            <td class="py-2 px-4 font-bold" colspan="3">{{ $category->name }}</td>
                        </tr>
                        @foreach($category->activities as $activity)
                            <tr class="border-b hover:bg-gray-50 cursor-pointer activity-row" data-activity-id="{{ $activity->id }}">
                                <td class="py-2 px-4">{{ $activity->name }}</td>
                                <td class="py-2 px-4 text-center">
                                    <label class="checkbox-container">
                                        <input type="checkbox" name="selected_activities[]" value="{{ $activity->id }}" {{ isset($eventActivities[$activity->id]) ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr class="hidden activity-details" id="activity-{{ $activity->id }}-details">
                                <td colspan="3" class="py-2 px-4">
                                    <div class="flex justify-between mb-2">
                                        <label class="gender-container" data-activity-id="{{ $activity->id }}" data-gender="M">
                                            <input type="checkbox" name="genders[{{ $activity->id }}][M]" value="M" class="hidden">
                                            <span class="gender-checkmark"></span> Male
                                        </label>
                                        <label class="gender-container" data-activity-id="{{ $activity->id }}" data-gender="F">
                                            <input type="checkbox" name="genders[{{ $activity->id }}][F]" value="F" class="hidden">
                                            <span class="gender-checkmark"></span> Female
                                        </label>
                                        <label class="gender-container" data-activity-id="{{ $activity->id }}" data-gender="Mix">
                                            <input type="checkbox" name="genders[{{ $activity->id }}][Mix]" value="Mix" class="hidden">
                                            <span class="gender-checkmark"></span> Mix
                                        </label>
                                    </div>
                                    @foreach(['M', 'F', 'Mix'] as $gender)
                                        <div class="pl-4 hidden gender-subs" id="activity-{{ $activity->id }}-gender-{{ $gender }}-subs">
                                            @foreach ($subs as $sub)
                                                <label class="block">
                                                    <input type="checkbox" name="subs[{{ $activity->id }}][{{ $gender }}][]" value="{{ $sub->id }}"> {{ $sub->name }}
                                                </label>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            
                <div class="flex justify-between mt-4">
                    <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="to-step-1">Previous</button>
                    <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="to-step-3">Next</button>
                </div>
        </div>


<!-- Paso 3: Maps -->
<div id="step-3" class="hidden">
    <h2 class="text-lg font-semibold mb-4">Lugar del Evento</h2>
    <select name="place_id" id="place_id" class="form-select mb-4">
        @foreach($places as $place)
            <option value="{{ $place->id }}">{{ $place->name }}</option>
        @endforeach
        <option value="nose">nose...</option>
        <option value="Otro">Otro...</option>
    </select>
    <p class="mb-4">Listado de lugares y opción de otro. Si selecciona "Otro", se muestra el mapa.</p>

    <div id="map-container" class="hidden">
        <gmpx-api-loader key="AIzaSyCiOsILiCTNFPbln2vBZpEtKXdx2JuceyU" solution-channel="GMP_CCS_autocomplete_v4">
        </gmpx-api-loader>
        <gmp-map id="map" center="40.749933,-73.98633" zoom="13" map-id="DEMO_MAP_ID">
            <div slot="control-block-start-inline-start" class="pac-card" id="pac-card">
                <div>
                    <div id="title">Autocomplete search</div>
                    <div id="type-selector" class="pac-controls">
                        <input type="radio" name="type" id="changetype-all" checked="checked" />
                        <label for="changetype-all">All</label>
                        <input type="radio" name="type" id="changetype-establishment" />
                        <label for="changetype-establishment">Establishment</label>
                        <input type="radio" name="type" id="changetype-address" />
                        <label for="changetype-address">Address</label>
                        <input type="radio" name="type" id="changetype-geocode" />
                        <label for="changetype-geocode">Geocode</label>
                        <input type="radio" name="type" id="changetype-cities" />
                        <label for="changetype-cities">(Cities)</label>
                        <input type="radio" name="type" id="changetype-regions" />
                        <label for="changetype-regions">(Regions)</label>
                    </div>
                    <br />
                    <div id="strict-bounds-selector" class="pac-controls">
                        <input type="checkbox" id="use-strict-bounds" value="" />
                        <label for="use-strict-bounds">Restrict to map viewport</label>
                    </div>
                </div>
                <gmpx-place-picker id="place-picker" for-map="map"></gmpx-place-picker>
            </div>
            <gmp-advanced-marker id="marker"></gmp-advanced-marker>
        </gmp-map>
        <div id="infowindow-content">
            <span id="place-name" class="title" style="font-weight: bold;"></span><br />
            <span id="place-address"></span>
        </div>
    </div>

    <input type="text" id="place-lat" name="lat">
    <input type="text" id="place-lon" name="lon">
    <input type="text" id="place-address-input" name="address">

    <div class="flex justify-between mt-4">
        <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="return-step2">Regresar</button>
        <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="to-step-4">siguiente</button>
    </div>
</div>
      
        

          <!-- Paso 4: Imgs -->
         <div id="step-4" class="hidden">
            imgs del sitio 
            
            <div class="flex justify-between mt-4">
                <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="return-step3">regresar</button>
                
                <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
            </div>
        </div> 

<!-------aqui van dos mapas el cual uno es para entrega de kits y otro es para lugar del evento--------->

