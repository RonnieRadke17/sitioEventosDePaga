        <!-- formulario funcional -->
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
                    <input type="number" name="capacity" id="capacity" class="w-full px-4 py-2 border rounded-lg" value="{{ old('capacity', $event->capacity ?? '') }}">
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
            <!--aqui se muestra el boton que muestra las actividades -->
            <div class="mb-4">
                <label for="is_with_activities" class="block text-gray-700">Actividades</label>
                <select name="is_with_activities" id="is_with_activities" class="w-full px-4 py-2 border rounded-lg">
                    <option value="0" {{ old('is_with_activities', isset($event) ? $event->activities : '') == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('is_with_activities', isset($event) ? $event->activities : '') == 1 ? 'selected' : '' }}>Yes</option>
                </select>
            </div>     
            
            <table class="min-w-full bg-white border border-gray-200" id="activity_table">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="py-2 px-4 text-left">Nombre</th>
                        <th class="py-2 px-4 text-left">Seleccionar</th>
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
            
                    <!-- Mostrar géneros y subgéneros asociados si la actividad está seleccionada -->
                    <tr class="activity-details" id="activity-{{ $activity->id }}-details">
                        <td colspan="2" class="py-2 px-4">
                            @foreach(['M', 'F', 'Mix'] as $gender)
                            <div class="mb-2">
                                <label class="block font-semibold">
                                    <input type="checkbox" name="genders[{{ $activity->id }}][{{ $gender }}]" value="{{ $gender }}"
                                    {{ (isset($eventActivities[$activity->id]) && $eventActivities[$activity->id]->where('gender', $gender)->count() > 0) || in_array($gender, old('genders.'.$activity->id, [])) ? 'checked' : '' }}>
                                    {{ $gender }}
                                </label>
            
                                <!-- Mostrar subgéneros si el género está seleccionado -->
                                <div class="pl-4 gender-subs" id="activity-{{ $activity->id }}-gender-{{ $gender }}-subs">
                                    @foreach ($subs as $sub)
                                    <label class="block">
                                        <input type="checkbox" name="subs[{{ $activity->id }}][{{ $gender }}][]" value="{{ $sub->id }}"
                                        {{ (isset($eventActivities[$activity->id]) && $eventActivities[$activity->id]->where('gender', $gender)->where('sub_id', $sub->id)->count() > 0) || in_array($sub->id, old('subs.'.$activity->id.'.'.$gender, [])) ? 'checked' : '' }}>
                                        {{ $sub->name }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </td>
                    </tr>
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
            <div class="mb-4">
                @error('place')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>   
            <div class="mb-4">
                <label for="place_id" class="block text-gray-700">Lugares</label>
                <select name="place_id" id="place_id" class="w-full px-4 py-2 border rounded-lg">
                    <option value="select">Selecciona un lugar</option>
                    @foreach($places as $place)
                        <option value="{{ $place->id }}" 
                            {{ old('place_id', isset($eventPlaceId) ? $eventPlaceId : '') == $place->id ? 'selected' : '' }}>
                            {{ $place->name }}
                        </option>
                    @endforeach
                    <option value="Otro" {{ old('place_id') == 'Otro' ? 'selected' : '' }}>Agregar uno nuevo</option>
                </select>



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
            </div>

            <!-- Campos ocultos para latitud, longitud, nombre y dirección -->
            <input type="hidden" id="place-lat" name="lat" >
            <input type="hidden" id="place-lng" name="lng" >
            <input type="hidden" id="place-name-input" name="place" >
            <input type="hidden" id="place-address-input" name="address">


            <div class="flex justify-between mt-4">
                <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="return-step2">Regresar</button>
                <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="to-step-4">siguiente</button>
            </div>
        </div>

        <!-- Paso 4: Imgs -->
        {{-- <div id="step-4" class="hidden">
            <h2 class="text-lg font-semibold mb-4">Imagenes</h2>
            
            <div class="mb-4" id="uploadImgs">
                <label for="cover">Imagen de portada:</label>
                <input type="file" name="cover">
                @error('cover')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4" id="uploadImgs">
                <label for="kit">Imagen del kit(opcional):</label>
                <input type="file" name="kit">
                @error('kit')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <div class="mb-4" id="uploadImgs">
                <label for="images">Imagenes de contenido(opcional):</label>
                <input type="file" id="images" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
            </div>
            
            <div class="mb-4 max-h-72 overflow-y-auto">
                <div id="preview-container" class="grid grid-cols-3 gap-4"></div>
            </div>

            <div class="mb-4">
                @if ($errors->has('images.*'))
                @foreach ($errors->get('images.*') as $messages)
                    @foreach ($messages as $message)
                        <span class="text-danger">{{ $message }}</span>
                    @endforeach
                @endforeach
                @endif
            </div>
            <div class="flex justify-between mt-4">
                <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="return-step3">regresar</button>
                
                <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
            </div>
        </div> 

        <script>
            function previewImages(event) {
                let previewContainer = document.getElementById('preview-container');
                previewContainer.innerHTML = '';
                let files = event.target.files;
                
                Array.from(files).forEach(file => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-32 object-cover">
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        </script> --}}

        <!-- Paso 4: Imgs -->
    {{-- <div id="step-4" class="hidden">
        <h2 class="text-lg font-semibold mb-4">Imagenes</h2>
        
        <!-- Imagen de portada -->
        <div class="mb-4" id="uploadImgs">
            <label for="cover">Imagen de portada:</label>
            <input type="file" name="cover" id="cover" accept="image/*" onchange="previewSingleImage(event, 'cover-preview')">
            
            <!-- Si existe una imagen previa -->
            @if ($errors->any() && old('cover'))
                <p>Imagen subida previamente: <img src="{{ asset('storage/' . old('cover')) }}" alt="Imagen portada previa" class="mt-2" id="cover-preview" style="max-width: 100px;" /></p>
            @endif

            <!-- Vista previa si hay una imagen seleccionada -->
            <img id="cover-preview" class="mt-2" style="display: none; max-width: 100px;"/>
            @error('cover')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Imagen del kit (opcional) -->
        <div class="mb-4" id="uploadImgs">
            <label for="kit">Imagen del kit (opcional):</label>
            <input type="file" name="kit" id="kit" accept="image/*" onchange="previewSingleImage(event, 'kit-preview')">
            
            <!-- Si existe una imagen previa -->
            @if ($errors->any() && old('kit'))
                <p>Imagen subida previamente: <img src="{{ asset('storage/' . old('kit')) }}" alt="Imagen kit previa" class="mt-2" id="kit-preview" style="max-width: 100px;" /></p>
            @endif

            <!-- Vista previa si hay una imagen seleccionada -->
            <img id="kit-preview" class="mt-2" style="display: none; max-width: 100px;"/>
            @error('kit')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Imágenes de contenido (opcional) -->
        <div class="mb-4" id="uploadImgs">
            <label for="images">Imágenes de contenido (opcional):</label>
            <input type="file" id="images" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
        </div>
        
        <div class="mb-4 max-h-72 overflow-y-auto">
            <div id="preview-container" class="grid grid-cols-3 gap-4"></div>
        </div>

        <div class="mb-4">
            @if ($errors->has('images.*'))
            @foreach ($errors->get('images.*') as $messages)
                @foreach ($messages as $message)
                    <span class="text-danger">{{ $message }}</span>
                @endforeach
            @endforeach
            @endif
        </div>

        <div class="flex justify-between mt-4">
            <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="return-step3">regresar</button>
            
            <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
        </div>
    </div> --}}


    <!-- Paso 4: Imgs -->
<div id="step-4" class="hidden">
    <h2 class="text-lg font-semibold mb-4">Imagenes</h2>
    
    <!-- Imagen de portada -->
    <div class="mb-4" id="uploadImgs">
        <label for="cover">Imagen de portada:</label>
        <input type="file" name="cover" id="cover" accept="image/*" onchange="previewSingleImage(event, 'cover-preview')">
        
        <!-- Mostrar imagen de portada existente -->
        @if (isset($coverImage))
            <p>Imagen portada actual:</p>
            <img src="{{ asset('storage/' . $coverImage->image) }}" alt="Imagen portada previa" class="mt-2" id="cover-preview" style="max-width: 100px;" />
        @endif

        <!-- Vista previa si hay una imagen seleccionada -->
        <img id="cover-preview" class="mt-2" style="display: none; max-width: 100px;"/>
        @error('cover')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Imagen del kit (opcional) -->
    <div class="mb-4" id="uploadImgs">
        <label for="kit">Imagen del kit (opcional):</label>
        <input type="file" name="kit" id="kit" accept="image/*" onchange="previewSingleImage(event, 'kit-preview')">
        
        <!-- Mostrar imagen del kit existente -->
        @if (isset($kitImage))
            <p>Imagen kit actual:</p>
            <img src="{{ asset('storage/' . $kitImage->image) }}" alt="Imagen kit previa" class="mt-2" id="kit-preview" style="max-width: 100px;" />
        @endif

        <!-- Vista previa si hay una imagen seleccionada -->
        <img id="kit-preview" class="mt-2" style="display: none; max-width: 100px;"/>
        @error('kit')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Imágenes de contenido (opcional) -->
    <div class="mb-4" id="uploadImgs">
        <label for="images">Imágenes de contenido (opcional):</label>
        <input type="file" id="images" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
        
        <!-- Mostrar imágenes de contenido existentes -->
        <div class="mt-2">
            @if(isset($contentImages))
            @foreach ($contentImages as $image)
                <img src="{{ asset('storage/' . $image->image) }}" alt="Imagen contenido previa" class="mt-2" style="max-width: 100px;" />
            @endforeach
            @endif
        </div>
    </div>

    <div class="mb-4 max-h-72 overflow-y-auto">
        <div id="preview-container" class="grid grid-cols-3 gap-4"></div>
    </div>

    <div class="mb-4">
        @if ($errors->has('images.*'))
        @foreach ($errors->get('images.*') as $messages)
            @foreach ($messages as $message)
                <span class="text-danger">{{ $message }}</span>
            @endforeach
        @endforeach
        @endif
    </div>

    <div class="flex justify-between mt-4">
        <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="return-step3">regresar</button>
        
        <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
    </div>
</div>


<!-- JavaScript para la vista previa -->
    <script>
        // Función para previsualizar una sola imagen (portada y kit)
        function previewSingleImage(event, previewId) {
            const file = event.target.files[0];
            const preview = document.getElementById(previewId);
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }

        // Función para previsualizar múltiples imágenes
        function previewImages(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = ''; // Limpiar las imágenes previas

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.classList.add('mt-2');
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    </script>

