        <!-- Paso 1: Event Details -->
        <div id="step-1">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Event Name</label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg" value="{{ old('name', $event->name ?? '') }}">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Description</label>
                <textarea name="description" id="description" class="w-full px-4 py-2 border rounded-lg">{{ old('description', $event->description ?? '') }}</textarea>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="event_date" class="block text-gray-700">Event Date</label>
                    <input type="text" name="event_date" id="event_date" class="w-full px-4 py-2 border rounded-lg" placeholder="YYYY-MM-DD HH:MM" value="{{ old('event_date', $event->event_date ?? '') }}">
                    @error('event_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-1/2 pl-2">
                    <label for="registration_deadline" class="block text-gray-700">Registration Deadline</label>
                    <input type="text" name="registration_deadline" id="registration_deadline" class="w-full px-4 py-2 border rounded-lg" placeholder="YYYY-MM-DD HH:MM" value="{{ old('registration_deadline', $event->registration_deadline ?? '') }}">
                    @error('registration_deadline')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        
            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="kit_delivery" class="block text-gray-700">Kit Delivery Date (Optional)</label>
                    <input type="text" name="kit_delivery" id="kit_delivery" class="w-full px-4 py-2 border rounded-lg" placeholder="YYYY-MM-DD HH:MM" value="{{ old('kit_delivery', $event->kit_delivery ?? '') }}">
                    @error('kit_delivery')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-1/2 pl-2">
                    <label for="is_limited_capacity" class="block text-gray-700">Limited Capacity</label>
                    <select name="is_limited_capacity" id="is_limited_capacity" class="w-full px-4 py-2 border rounded-lg">
                        <option value="0" {{ old('is_limited_capacity', $event->is_limited_capacity ?? 0) == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('is_limited_capacity', $event->is_limited_capacity ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                    </select>
                    @error('is_limited_capacity')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        
            <div class="" id="div-content">
                <div class="mb-4 hidden" id="capacity-field">
                    <label for="capacity" class="block text-gray-700">Capacity</label>
                    <input type="number" name="capacity" id="capacity" class="w-full px-4 py-2 border rounded-lg" min="1" value="{{ old('capacity', $event->capacity ?? '') }}">
                    @error('capacity')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4" id="price-content">
                    <label for="price" class="block text-gray-700">Price</label>
                    <input type="number" name="price" id="price" class="w-full px-4 py-2 border rounded-lg" step="0.01" min="0" value="{{ old('price', $event->price ?? '') }}">
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            @if ($mode == 'Editar')
            <div class="mb-4">
                <label for="status" class="block text-gray-700">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 border rounded-lg">
                    <option value="Activo" {{ old('status', $event->status ?? '') == 'Activo' ? 'selected' : '' }}>Active</option>
                    <option value="Inactivo" {{ old('status', $event->status ?? '') == 'Inactivo' ? 'selected' : '' }}>Inactive</option>
                    <option value="Cancelado" {{ old('status', $event->status ?? '') == 'Cancelado' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            @endif
        
            <div class="flex justify-center mt-4">
                <button type="button" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2" id="to-step-2">Next</button>
            </div>
        </div>


        <!-- Paso 2: Activities -->
        <!-- revisar la tabla todo lo relacionado con mostrar info-->
        <div id="step-2" class="hidden">
            {{-- aqui se muestra el boton que muestra las actividades --}}
            <div class="mb-4">
                <label for="is_with_activities" class="block text-gray-700">Actividades</label>
                <select name="is_with_activities" id="is_with_activities" class="w-full px-4 py-2 border rounded-lg">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            {{-- tabla anterior --}}
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
                <option value="Otro">Agregar uno nuevo</option>
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

            {{-- informacion del 1er mapa --}}
            <input type="text" id="place-lat" name="lat">
            <input type="text" id="place-lng" name="lng">
            <input type="text" id="place-name-input" name="place">
            <input type="text" id="place-address-input" name="address">

            <div class="flex justify-between mt-4">
                <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="return-step2">Regresar</button>
                <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="to-step-4">siguiente</button>
            </div>
        </div>

          <!-- Paso 4: Imgs -->
         <div id="step-4" class="hidden">
            imgs del sitio 

            aqui mostramos las images que halla del evento y de la ui
            para que pueda seleccionar las que quiera pero tambien pueda subir las que quiera
            
            <div class="flex justify-between mt-4">
                <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="return-step3">regresar</button>
                
                <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
            </div>
        </div> 

<!-------aqui van dos mapas el cual uno es para entrega de kits y otro es para lugar del evento--------->

