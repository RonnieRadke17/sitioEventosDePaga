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


{{-- link categorias del evento --}}
<a href="{{ route('category-events.form', $id) }}">
    {{ $categories == null ? 'agregar categorias' : 'modificar categorias' }}
</a>
<br>
{{-- link actividades del evento --}}
<a href="{{ route('activity-events.form', $id) }}">
    {{ $activities == null ? 'agregar actividades' : 'modificar actividades' }}
</a>
<br>
{{-- link del mapa --}}
<a href="{{ route('event-map.form', $id) }}">
    {{ $map == null ? 'agregar ubicación' : 'modificar ubicación' }}
</a>
<br>

{{-- link del form de multimedia del evento --}}
{{-- aqui igual se define si se pone el apartado de la imagen del kit --}}
{{-- <a href="{{ route('event-multimedia.form', $id) }}">
    {{ $multimedia == null ? 'agregar multimedia' : 'modificar multimedia' }}
</a> --}}

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
