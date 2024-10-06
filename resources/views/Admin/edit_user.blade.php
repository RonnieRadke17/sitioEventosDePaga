@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Editar Usuario</h1>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label class="block text-gray-700">Nombre:</label>
            <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded py-2 px-3" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Apellido Paterno:</label>
            <input type="text" name="paternal" value="{{ $user->paternal }}" class="w-full border rounded py-2 px-3" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Apellido Materno:</label>
            <input type="text" name="maternal" value="{{ $user->maternal }}" class="w-full border rounded py-2 px-3">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Correo Electrónico:</label>
            <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded py-2 px-3" required>
        </div>

        <!-- Nuevo campo para editar la fecha de nacimiento -->
        <div class="mb-4">
            <label class="block text-gray-700">Fecha de Nacimiento:</label>
            <input type="date" name="birthdate" value="{{ $user->birthdate->format('Y-m-d') }}" class="w-full border rounded py-2 px-3" required>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection