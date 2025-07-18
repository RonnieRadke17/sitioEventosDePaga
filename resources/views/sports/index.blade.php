@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de Deportes</h1>

    <a href="{{ route('sports.create') }}" class="btn btn-primary mb-3">Crear Deporte</a>

    
    <ul>
        @foreach($sports as $sport)
            <li>
                {{$sport->name }}
                <a href="{{route('sports.edit', $sport->encrypted_id)}}" class="btn btn-warning">Editar</a>
            </li>
        @endforeach
    </ul>
    


@endsection
