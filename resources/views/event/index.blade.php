@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection
@section('content')
    @if(Session::has('mensaje'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{Session::get('mensaje')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- <a href="{{ route('event.create') }}" class="btn btn-se rounded-circle position-fixed" style="bottom: 20px; left: 20px; width: 50px; height: 50px; display: flex; justify-content: center; align-items: center;"><i class="fas fa-pencil-alt"></i></a> --}}
    <a href="{{ route('event.create') }}" class="btn btn-se rounded-circle position-fixed">Crear evento</a>{{-- btn modificar --}}
    
    <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        @foreach($events as $event)
            
            <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <a href="#">
                    <img class="w-full h-48 object-cover" src="{{ asset('storage/'.$event->first_image) }}" alt="Event Image">
                </a>
                <div class="p-5">
                    <a href="#">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $event->name }}</h5>
                    </a>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $event->event_date }}</p>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ Str::limit($event->description, 100) }}</p>
                    <a href="{{ route('event.show',encrypt($event->id)) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Ver más
                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                        </svg>
                    </a>
                </div>
            </div>

        @endforeach

    </div>
    

    

{{-- <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img class="w-full h-48 object-cover" src="{{ asset('storage/'.$event->first_image) }}" alt="Event Image">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $event->name }}</h2>
                    <p class="text-gray-600 mt-2">{{ $event->event_date }}</p>
                    <p class="text-gray-600 mt-2">{{ Str::limit($event->description, 100) }}</p>
                    <div class="flex justify-between mt-4 space-x-2">
                    <a href="{{ route('event.show',encrypt($event->id)) }}" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded">View</a>
                    <a href="{{ route('event.edit',encrypt($event->id)) }}" class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded">Edit</a>
                    <form action="{{ route('event.destroy',encrypt($event->id)) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded">Delete</button>
                    </form>
                </div>
                <a href="{{ route('registrations', ['event_id' => $event->id]) }}" class="mt-4 block text-center bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Ver Inscritos</a>
                            </div>
            </div> --}}



@endSection