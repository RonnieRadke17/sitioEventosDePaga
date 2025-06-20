@extends('layouts.app')

@section('head')
    <style>
        .scroll-container {
            max-height: 60vh; /* Ajusta esto según sea necesario */
            overflow-y: auto;
        }
    </style>
@endsection

@section('content')

<!-- Formulario -->
<form action="{{ route('event.store') }}" method="post" class="max-w-md mx-auto p-6 bg-white shadow-lg rounded-lg border border-gray-200 dark:text-white dark:bg-gray-600 dark:border-gray-600">
    @csrf
    @include('event.form', ['mode' => 'Registrar'])
</form>
@endsection
