<div id="container">

    <h2 class="text-3xl font-bold text-center text-orange-600 mb-2">{{$mode}} evento</h2>

    <x-forms.input-text name="name" description="Nombre del evento" oldvalue="{{ $event->name ?? ''}}"></x-forms.input-text>

    {{-- <div class="mb-4">
        <div class="relative">
            <input type="text"  name="name" id="name" autocomplete="off" value="{{ old('name', $event->name ?? '') }}" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
            <label for="name" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Nombre del evento</label>
        </div>
        @error('name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div> --}}

    <x-forms.input-text name="description" id="description" description="Descripción del evento" oldvalue="{{ $event->description ?? ''}}"></x-forms.input-text>
    
    {{-- <div class="mb-4">
        <div class="relative">
            <input type="text"  name="description" id="description" autocomplete="off" value="{{ old('description', $event->description ?? '') }}" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
            <label for="description" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Descripción del evento</label>
        </div>
        @error('description')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div> --}}

    {{-- <div class="flex mb-4">
        <div class="w-1/2 pr-2">
            <div class="relative max-w-sm">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                </svg>
                </div>
                <input datepicker id="event_date" name="event_date" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" autocomplete="off" placeholder="Fecha del evento" value="{{ old('event_date', $event->event_date ?? '') }}">
            </div>
            @error('event_date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="w-1/2 pl-2">
            
            <div class="relative max-w-sm">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                </svg>
                </div>
                <input datepicker id="registration_deadline" name="registration_deadline" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" autocomplete="off" placeholder="Fecha limite" value="{{ old('registration_deadline', $event->registration_deadline ?? '') }}">
            </div>
            @error('registration_deadline')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div> --}}

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


    <div class="mb-4">
        <div class="relative">
            <input type="text"  name="capacity" id="capacity" autocomplete="off" value="{{ old('capacity', $event->capacity ?? '') }}"  class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "/>
            <label for="name" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Capacidad(Opcional)</label>
        </div>
        @error('capacity')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4">
        <div class="relative">
            <input type="text"  name="price" id="price" autocomplete="off" value="{{ old('price', $event->price ?? '') }}"  class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "/>
            <label for="name" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Precio</label>
        </div>
        @error('price')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
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


