
@extends('layouts.app')

@section('content')

<!-- Contenido principal -->
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Mis Eventos Registrados</h1>

    @if ($events->isEmpty())
        <p class="text-center text-gray-500">No te has registrado en ningún evento.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($events as $event)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105">
                    <!-- Mostrar la imagen del evento si existe -->
                    @if ($event->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $event->images->first()->image) }}" class="w-full h-48 object-cover" alt="{{ $event->name }}">
                    @else
                        <img src="{{ asset('images/default-event.jpg') }}" class="w-full h-48 object-cover" alt="{{ $event->name }}">
                    @endif

                    <!-- Detalles del evento -->
                    <div class="p-4">
                        <h5 class="text-xl font-semibold mb-2">{{ $event->name }}</h5>
                        <p class="text-gray-700 text-sm mb-4">{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p>
                        <a href="{{ route('user-event.show', encrypt($event->id)) }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Ver detalles</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection