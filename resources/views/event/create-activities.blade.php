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
@endsection

@section('content')
<!-- Formulario -->
<form action="{{ route('activities-event.store') }}" method="post" class="max-w-md mx-auto p-6 bg-white shadow-lg rounded-lg border border-gray-200 dark:text-white dark:bg-gray-600 dark:border-gray-600">
    @csrf
    <input type="hidden" name="id_event" value="{{$id}}">
    @include('event.form-activities', ['mode' => 'Registrar'])
</form>

@endsection
