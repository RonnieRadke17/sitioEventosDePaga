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
    {{-- stilo de las imgs del sistema --}}
    <style>
        .scroll-container {
            max-height: 60vh; /* Ajusta esto según sea necesario */
            overflow-y: auto;
        }
    </style>
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
        <form action="{{ route('event.store') }}" method="post" id="multi-step-form" enctype="multipart/form-data">
            @csrf
            @include('event.form',['mode'=>'Registrar'])
        </form>
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{-- <script>
    //mostramos las imagenes
    document.getElementById('imgsSelected').addEventListener('change', function() {
            const imgsBefore = document.getElementById('imgsBefore');
            const uploadImgs = document.getElementById('uploadImgs');

            if (this.value == 'invalid') {
                console.log(this.value);
                imgsBefore.classList.add('hidden');
                uploadImgs.classList.add('hidden');

            } else if(this.value == 'before'){
                console.log(this.value);
                imgsBefore.classList.remove('hidden');
                uploadImgs.classList.add('hidden');
                
            } else if(this.value == 'new'){
                console.log(this.value);
                imgsBefore.classList.add('hidden');
                uploadImgs.classList.remove('hidden');

            }else if(this.value == 'combined'){
                console.log(this.value);
                imgsBefore.classList.remove('hidden');
                uploadImgs.classList.remove('hidden');
            }
        });
</script> --}}
@endsection
