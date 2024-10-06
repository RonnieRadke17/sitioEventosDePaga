@extends('layouts.app')

@section('content')
<div class="container bg-gray-100 p-10">
    @if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{Session::get('mensaje')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    <!-- <a href="{{url('event/create')}}" class="btn btn-success">Registrar</a> -->
    <a href="{{ route('event.create') }}" class="btn btn-se rounded-circle position-fixed" style="bottom: 20px; left: 20px; width: 50px; height: 50px; display: flex; justify-content: center; align-items: center;"><i class="fas fa-pencil-alt"></i></a>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        @foreach($events as $event)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
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
            </div>
        @endforeach
    </div>
    
</div>
@endSection