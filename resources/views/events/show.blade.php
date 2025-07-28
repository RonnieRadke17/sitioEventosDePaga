@extends('layouts.app')
@section('title','Evento')
@section('head')
    <style>
        .scroll-container {
            max-height: 60vh; /* Ajusta esto según sea necesario */
            overflow-y: auto;
        }
    </style>
@endsection
@section('content')

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('success') }}
    </div>
@endif
{{-- Verifica si hay errores en la sesión --}}
@if(session('errors'))
    <div class="alert alert-danger">
        {{ session('errors')->first() }}
    </div>
@endif

<a href="{{ route('events.edit',$id) }}">Editar del evento</a>
{{$id}}
{{-- información del evento --}}

<div class="scroll-container">
    <h2 class="text-2xl font-bold mb-4">Información del Evento</h2>
    <p><strong>Nombre:</strong> {{ $event->name }}</p>
    <p><strong>Descripción:</strong> {{ $event->description }}</p>
    <p><strong>Fecha del Evento:</strong> {{ $event->event_date }}</p>
    <p><strong>Fecha Límite de Inscripción:</strong> {{ $event->registration_deadline }}</p>
    <p><strong>Capacidad:</strong> {{ $event->capacity }}</p>
    <p><strong>Precio:</strong> {{ $event->price }}</p>
    <p><strong>Estado:</strong> {{ $event->status }}</p>
    <p><strong>Fecha de Creación:</strong> {{ $event->created_at }}</p>
    <p><strong>Fecha de Actualización:</strong> {{ $event->updated_at }}</p>
    
</div>


{{-- link del form de multimedia del evento --}}

{{-- link del mapa --}}

{{-- link categorias del evento --}}
@if ($categories)
    <a href="{{ route('category-events.form',$id) }}">categorias</a>
@else
    <a href="{{ route('category-events.form',$id) }}">categorias</a>
@endif
{{-- link actividades del evento --}}


{{-- link para desactivar el evento --}}
@if($event->trashed())
                        <form 
                            action="{{ route('events.restore',$id) }}" 
                            method="POST" 
                            style="display:inline;"
                            >
                            @csrf
                            <button class="btn btn-sm btn-outline-success">
                                Activar
                            </button>
                        </form>

                        <form 
                            action="{{ route('events.forceDelete', $id) }}" 
                            method="POST" 
                            class="d-inline"
                            onsubmit="return confirm('¿Seguro de eliminar?')"
                            >
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    @else
                        <form 
                        action="{{route('events.destroy',$id)}}" 
                        method="POST" 
                        class="d-inline"
                        onsubmit="return confirm('¿Seguro de desactivar?')"
                        >
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Desactivar</button>
                        </form>
                    @endif

@endsection
