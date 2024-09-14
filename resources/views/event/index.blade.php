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

    <a href="{{url('event/create')}}" class="btn btn-success">Registrar</a>
 
    <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        @foreach($events as $event)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img class="w-full h-48 object-cover" src="{{ asset('storage/'.$event->first_image) }}" alt="Event Image">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $event->name }}</h2>
                    <p class="text-gray-600 mt-2">{{ $event->event_date }}</p>
                    <p class="text-gray-600 mt-2">{{ Str::limit($event->description, 100) }}</p>
                    <div class="mt-4">
                        <a href="{{ route('event.show',encrypt($event->id)) }}" class="text-indigo-600 hover:text-indigo-800">View</a>
                        <a href="{{ route('event.edit',encrypt($event->id)) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                    </div>
                    <form action="{{ route('event.destroy',encrypt($event->id)) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-indigo-600 hover:text-indigo-800">Delete</button>
                    </form>
                    
                </div>
            </div>
        @endforeach
    </div>
    
</div>
@endSection