    <div id="container">

            <h2 class="text-3xl font-bold text-center text-orange-600 mb-2">{{$mode}} evento</h2>

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
                    <div class="relative">
                        <input type="text" name="price" id="price" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-dark bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                        <label for="price" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Floating outlined</label>
                    </div>
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
                <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
            </div>
    </div>
 
