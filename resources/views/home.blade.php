@extends('layouts.app')
@section('title','Página principal')
@section('content')
    <!-- estos deben ir en app en la navbar-->
    
    {{-- aqui dependiendo de la fecha de nacimiento se le muestran ciertos eventos --}}

    
    <div class="container bg-gray-100 p-10">
        <h1 class="mb-4">Eventos</h1>
        <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            @foreach($events as $event)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <img class="w-full h-48 object-cover" src="{{ asset('storage/'.$event->first_image) }}" alt="Event Image">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $event->name }}</h2>
                        <p class="text-gray-600 mt-2">{{ $event->event_date }}</p>
                        <p class="text-gray-600 mt-2">{{ Str::limit($event->description, 100) }}</p>
                        <div class="mt-4">
                            <a href="{{ route('event.show', $event->id) }}" class="text-indigo-600 hover:text-indigo-800">Ver mas</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
    </div>
    

@endsection
