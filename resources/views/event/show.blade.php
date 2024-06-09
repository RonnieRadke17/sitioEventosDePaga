@extends('layouts.app')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="card">
        <div class="card-header bg-secondary text-white text-center">
            <h4 class="mb-0">Detalles del Evento</h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $evento->Nombre }}</h5>
            <p class="card-text ">
                <strong>Descripción:</strong> {{ $evento->Descripcion }}<br>
                <strong>Fecha:</strong> {{ $evento->Fecha }}<br>
                <strong>Hora:</strong> {{ $evento->Hora }}<br>
                <strong>Tipo:</strong> {{ $evento->Tipo }}<br>
                <strong>Lugar:</strong> {{ $evento->Lugar }}<br>
                @if($evento->imagen)
                    <img src="{{ asset('storage/' . $evento->imagen) }}" class="img-fluid mx-auto d-block" style="max-height: 200px;" alt="Imagen del evento">
                @endif
            </p>
        </div>
        <div class="card-footer text-center">
            <a href="{{ url('evento') }}" class="btn btn-primary">Volver</a>
        </div>
    </div>
</div>
@endsection
