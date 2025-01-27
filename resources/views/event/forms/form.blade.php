        <!-- formulario funcional -->
        <!-- Paso 1: Event Details -->
        <div id="step-1">

            <h2 class="text-3xl font-bold text-center text-orange-600 mb-2">Registro de evento</h2>

            <div class="mb-4">
                <div class="relative">
                    <input type="text"  name="name" id="name" autocomplete="off" value="{{ old('name', $event->name ?? '') }}" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                    <label for="name" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Nombre del evento</label>
                </div>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <div class="relative">
                    <input type="text"  name="description" id="description" autocomplete="off" value="{{ old('description', $event->description ?? '') }}" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                    <label for="description" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Descripción del evento</label>
                </div>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    
                    <label for="event_date" class="block text-gray-700 dark:text-white">Fecha del evento</label>
                    <input type="text" name="event_date" id="event_date" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="YYYY-MM-DD HH:MM" value="{{ old('event_date', $event->event_date ?? '') }}">
                    @error('event_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-1/2 pl-2">
                    <label for="registration_deadline" class="block text-gray-700 dark:text-white">Fecha límite de inscripción</label>
                    <input type="text" name="registration_deadline" id="registration_deadline" class="w-full px-4 py-2  dark:bg-gray-800 border rounded-lg" placeholder="YYYY-MM-DD HH:MM" value="{{ old('registration_deadline', $event->registration_deadline ?? '') }}">
                    @error('registration_deadline')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        
            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="kit_delivery" class="block text-gray-700 dark:text-white">Fecha de entrega del kit (Opcional)</label>
                    <input type="text" name="kit_delivery" id="kit_delivery" class="w-full px-4 py-2 border rounded-lg" placeholder="YYYY-MM-DD HH:MM" value="{{ old('kit_delivery', $event->kit_delivery ?? '') }}">
                    @error('kit_delivery')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-1/2 pl-2">
                    <label for="is_limited_capacity" class="block text-gray-700 dark:text-white">Capacidad limitada</label>
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
                    {{-- <div class="relative">
                        <input type="text" id="floating_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-dark bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                        <label for="floating_outlined" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Floating outlined</label>
                    </div> --}}
                </div>
                
                <div class="mb-4" id="price-content">
                    <label for="price" class="block text-gray-700">Price</label>
                    <input type="number" name="price" id="price" class="w-full px-4 py-2 border rounded-lg" step="0.01" min="0" value="{{ old('price', $event->price ?? '') }}">
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    {{-- <div class="relative">
                        <input type="text" name="price" id="price" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-dark bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                        <label for="price" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Floating outlined</label>
                    </div> --}}
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
                {{-- <button type="button" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2" id="to-step-2">Next</button> --}}
                <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
            </div>
        </div>

        <!-- Paso 2: Activities -->
        <!-- revisar la tabla todo lo relacionado con mostrar info-->
        <div id="step-2" class="hidden">
            <!--aqui se muestra el boton que muestra las actividades -->
            
            {{-- <div class="mb-4">
                <label for="is_with_activities" class="block text-gray-700">Actividades</label>
                <select name="is_with_activities" id="is_with_activities" class="w-full px-4 py-2 border rounded-lg">
                    <option value="0" {{ old('is_with_activities', isset($event) ? $event->activities : '') == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('is_with_activities', isset($event) ? $event->activities : '') == 1 ? 'selected' : '' }}>Yes</option>
                </select>
            </div> --}}
        

            @foreach($activities as $activity)
                <div class="flex items-center space-x-4 space-y-2">
                    <input type="checkbox" id="activity-{{ $activity->id }}" name="selected_activities[]" value="{{ $activity->id }}" class="hidden peer">
                    <label for="activity-{{ $activity->id }}" class="
                        flex items-center justify-between w-full p-2 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer 
                        dark:hover:text-gray-300 dark:border-gray-700 
                        peer-checked:border-blue-600 hover:text-gray-600 
                        dark:peer-checked:text-gray-300 peer-checked:text-gray-600 
                        hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <div class="block">
                            <span class="text-lg font-semibold">{{ $activity->name }}</span>
                        </div>
                    </label>
                </div>
            @endforeach



            
        




            

            
            

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


    {{-- detalles de las actividades --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ocultar todos los detalles de actividad por defecto
            document.querySelectorAll('.activity-details').forEach(function(detailsRow) {
                detailsRow.classList.add('hidden');
            });
    
            // Evento para mostrar/ocultar detalles de actividad
            document.querySelectorAll('.activity-checkbox').forEach(function(activityCheckbox) {
                activityCheckbox.addEventListener('change', function() {
                    var activityId = this.closest('tr').dataset.activityId;
                    var detailsRow = document.getElementById('activity-' + activityId + '-details');
    
                    if (this.checked) {
                        detailsRow.classList.remove('hidden');
                    } else {
                        detailsRow.classList.add('hidden');
                        // Desmarcar géneros y subgéneros si se deselecciona la actividad
                        detailsRow.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                            checkbox.checked = false;
                        });
                    }
                });
    
                // Mostrar detalles si el checkbox está preseleccionado al cargar
                if (activityCheckbox.checked) {
                    var activityId = activityCheckbox.closest('tr').dataset.activityId;
                    var detailsRow = document.getElementById('activity-' + activityId + '-details');
                    detailsRow.classList.remove('hidden');
                }
            });
    
            // Evento para mostrar/ocultar subgéneros según el género seleccionado
            document.querySelectorAll('.gender-checkbox').forEach(function(genderCheckbox) {
                genderCheckbox.addEventListener('change', function() {
                    var genderId = this.name.match(/\[(.*?)\]/g)[1].replace(/[\[\]]/g, '');
                    var activityId = this.name.match(/\[(.*?)\]/)[1];
                    var subsDiv = document.getElementById('activity-' + activityId + '-gender-' + genderId + '-subs');
    
                    if (this.checked) {
                        subsDiv.classList.remove('hidden');
                    } else {
                        subsDiv.classList.add('hidden');
                        // Desmarcar subgéneros si se deselecciona el género
                        subsDiv.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                            checkbox.checked = false;
                        });
                    }
                });
    
                // Mostrar subgéneros si el género está preseleccionado al cargar
                if (genderCheckbox.checked) {
                    var genderId = genderCheckbox.name.match(/\[(.*?)\]/g)[1].replace(/[\[\]]/g, '');
                    var activityId = genderCheckbox.name.match(/\[(.*?)\]/)[1];
                    var subsDiv = document.getElementById('activity-' + activityId + '-gender-' + genderId + '-subs');
                    subsDiv.classList.remove('hidden');
                }
            });
        });
    </script>

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