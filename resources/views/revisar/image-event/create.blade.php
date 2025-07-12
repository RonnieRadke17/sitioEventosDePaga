@extends('layouts.app')

@section('head')
@vite(['resources/js/activities.js']) 
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.0.0/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js" defer></script>
    <style>
        .scroll-container {
            max-height: 60vh; /* Ajusta esto según sea necesario */
            overflow-y: auto;
        }
    </style>
    <title>Imagenes</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        #map { height: 300px; }
    </style>
@endsection

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{route('images-event.store')}}" method="POST" class="max-w-md mx-auto p-6 bg-white shadow-lg rounded-lg border border-gray-200 dark:text-white dark:bg-gray-600 dark:border-gray-600" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{$id}}">
    @error('id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
   @include('image-event.form',['mode'=>'Agregar']);
</form>


<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('preview');
        previewContainer.innerHTML = ''; // Limpiar vista previa

        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgElement = document.createElement('img');
                imgElement.src = e.target.result;
                imgElement.classList.add('w-full', 'h-24', 'object-cover', 'rounded');
                previewContainer.appendChild(imgElement);
            };
            reader.readAsDataURL(file);
        });
    });
</script>

@endsection
