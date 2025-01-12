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

    {{-- script que muestra el contenido el en form y el mapa del evento revisar los items de las subs--}}
    <script src="{{ asset('js/formContent.js') }}"></script>
@endsection


{{-- aqui se revisa el valor del cual se va a usar para el evento --}}


@section('content')
<!-- Formulario -->
<form action="{{ route('event.store') }}" method="post" id="multi-step-form" enctype="multipart/form-data" class="max-w-md mx-auto p-6 bg-white shadow-lg rounded-lg border border-gray-200 dark:text-white dark:bg-gray-600 dark:border-gray-600">
    @csrf

    @if($action == 'data')
    
    @endif

    @include('event.form',['mode'=>'Registrar'])
</form>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


@endsection
