@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Usuarios registrados para {{ $event->name }}</h1>
    
    @if($registeredUsers->isEmpty())
        <p class="text-center text-gray-500">No hay usuarios registrados en este evento.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-800 text-white">Nombre</th>
                        <th class="py-2 px-4 bg-gray-800 text-white">Correo Electrónico</th>
                        <th class="py-2 px-4 bg-gray-800 text-white">Fecha de Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registeredUsers as $user)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                            <td class="py-2 px-4 border-b">{{ $user->pivot->created_at->format('d/m/Y') }}</td> <!-- Suponiendo que tienes timestamps en la tabla pivote -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
