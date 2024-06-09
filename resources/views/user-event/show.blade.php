@extends('layouts.app')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="card">
        <div class="card-header bg-secondary text-white text-center">
            <h4 class="mb-0">Detalles del evento</h4>
            <h4 class="mb-0">aqui va toda la informacion del evento</h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $event->name }}</h5>
            <p class="card-text ">
                <strong>fecha:</strong> {{ $event->date }}<br>
                <strong>hora de inicio:</strong> {{ $event->start_time }}<br>
                <strong>hora de fin:</strong> {{ $event->end_time }}<br>
                <strong>Descripción:</strong> {{ $event->description }}<br>
                <strong>Precio:</strong> {{ $event->price }}<br>
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" class="img-fluid mx-auto d-block" style="max-height: 200px;" alt="Imagen del event">
                @endif
            </p>
            <a href="{{ route('events.purchase', $event->id) }}" class="btn btn-primary">comprar</a>
        </div>
        <div class="card-footer text-center">
            <a href="{{ url('event') }}" class="btn btn-primary">Volver</a>
        </div>
    </div>
</div>
@endsection
