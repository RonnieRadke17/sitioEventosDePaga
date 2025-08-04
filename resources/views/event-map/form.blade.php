@extends('layouts.app')
@section('title', 'Mapa del Evento')
 
@section('content')
    hola
    {{ $id }}

    <h2 class="text-3xl font-bold text-center text-orange-600 mb-2">Mapa del Evento: {{$event->name}}</h2>


   <select name="place_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
           dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

    <option value="">Selecciona un lugar</option>

    @foreach ($places as $place)
        <option value="{{ $place->id }}" {{ $selectedPlace == $place->id ? 'selected' : '' }}>
            {{ $place->name }}
        </option>
    @endforeach
</select>




@endsection 
 
 
 {{-- <div id="container">

            <h2 class="text-3xl font-bold text-center text-orange-600 mb-2">{{$mode}} mapa</h2>
        <div id="step-2">
                @csrf
                <select id="option" name="option" class="block w-full p-2 mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="notaviable" selected>Seleccionar ubicación registrada</option>
                    @foreach ($places as $place)
                        <option value="{{ encrypt($place->id) }}">{{ $place->name }}</option>
                    @endforeach
                    <option value="new">Agregar ubicación</option>
                </select>
                @error('option')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
                <div id="map" class=""></div>
                <input type="hidden" name="name" id="name">
                <input type="hidden" name="address" id="address">
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lon" id="lon">
                <p>© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors</p>
            
        </div>
            <div class="flex justify-center mt-4">
                <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
            </div>
    </div> --}}

{{--     <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        var map = L.map('map').setView([19.4326, -99.1332], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var geocoder = L.Control.geocoder({
            defaultMarkGeocode: false,
            position: 'topright'
        }).addTo(map);

        geocoder.on('markgeocode', function (e) {
            var latlng = e.geocode.center; // Obtener latitud y longitud
            var name = e.geocode.name; // Obtener el nombre del lugar
            var address = e.geocode.properties.display_name; // Obtener la dirección completa

            // Asignar los valores a los campos ocultos
            document.getElementById('name').value = name;
            document.getElementById('address').value = address;
            document.getElementById('lat').value = latlng.lat;
            document.getElementById('lon').value = latlng.lng;

            // Añadir un marcador en la ubicación encontrada
            L.marker(latlng).addTo(map)
                .bindPopup(name) // Mostrar el nombre del lugar en un popup
                .openPopup();
        });
    </script> --}}
    
    
 
{{-- <script>
    document.getElementById('small').addEventListener('change', function () {
    var mapDiv = document.getElementById('map'); // Obtener el div del mapa

    if (this.value === 'new') {
        mapDiv.classList.remove('hidden'); // Mostrar el mapa
    } else {
        mapDiv.classList.add('hidden'); // Ocultar el mapa
    }
});
</script> --}}