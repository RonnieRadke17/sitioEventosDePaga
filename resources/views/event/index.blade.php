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
    
    {{-- <table class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>{{$event->id}}</td>
                <td>{{$event->name}}</td>
                <td>{{$event->date}}</td>
                <td><img class="img-thumbnail img-fluid" src="{{asset('storage').'/'.$event->image}}" height="100px" width="100px" alt=""></td>
                <td><a href="{{route('event.show', $event->id)}}" class="btn btn-warning">Ver</a> </td>
                <td>
                    <a href="{{route('event.edit', $event->id)}}" class="btn btn-warning">Editar</a> 
                    |
                    <form action="{{route('event.destroy', $event->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que desea eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table> --}}
    <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        @foreach($events as $event)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img class="w-full h-48 object-cover" src="{{ asset('storage/'.$event->first_image) }}" alt="Event Image">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $event->name }}</h2>
                    <p class="text-gray-600 mt-2">{{ $event->event_date }}</p>
                    <p class="text-gray-600 mt-2">{{ Str::limit($event->description, 100) }}</p>
                    <div class="mt-4">
                        <a href="{{ route('event.show', $event->id) }}" class="text-indigo-600 hover:text-indigo-800">Learn more</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    
</div>
@endSection