@extends('layouts.app')
@section('head')
{{-- script del calendario --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="{{ asset('js/calendar.js') }}"></script>
{{-- style del mapa --}}
<link rel="stylesheet" href="/css/mapStyle.css"> 
{{-- script del mapa --}}
<script src="{{ asset('js/map.js') }}"></script>
<script type="module" src="https://unpkg.com/@googlemaps/extended-component-library@0.6"></script>

{{-- estilos de los checkbox --}}
<link rel="stylesheet" href="/css/checkboxStyle.css">

{{-- script que muestra el contenido el en form y el mapa del evento --}}
<script src="{{ asset('js/formContent.js') }}"></script>

@endsection
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full md:w-2/3 lg:w-1/2">
        <!-- Step indicators -->
        <div class="mb-6 flex justify-between items-center">
            <div class="w-1/3 text-center">
                <span class="block w-8 h-8 bg-blue-500 text-white rounded-full mx-auto">1</span>
                <span class="text-gray-700 text-sm">Event Details</span>
            </div>
            <div class="w-1/3 text-center">
                <span class="block w-8 h-8 bg-gray-300 text-white rounded-full mx-auto">2</span>
                <span class="text-gray-700 text-sm">Activities</span>
            </div>
        </div>
        
        <!-- Formulario -->
        <form action="{{ route('event.store') }}" method="post" id="multi-step-form">
            @csrf
            @include('event.form',['mode'=>'Registrar'])
        
        </form>
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


@endsection
