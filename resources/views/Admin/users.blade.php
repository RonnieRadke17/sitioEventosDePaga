@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Todos los Usuarios Registrados</h1>

    <!-- Formulario de búsqueda -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-center mb-4">Buscar Usuarios</h2>
        <form action="{{ route('admin.users.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" id="name" placeholder="Buscar por nombre" value="{{ request('name') }}" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Apellido Paterno -->
                <div>
                    <label for="paternal" class="block text-sm font-medium text-gray-700">Apellido Paterno</label>
                    <input type="text" name="paternal" id="paternal" placeholder="Buscar por apellido paterno" value="{{ request('paternal') }}" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Apellido Materno -->
                <div>
                    <label for="maternal" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
                    <input type="text" name="maternal" id="maternal" placeholder="Buscar por apellido materno" value="{{ request('maternal') }}" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Edad -->
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700">Edad</label>
                    <input type="number" name="age" id="age" placeholder="Edad" value="{{ request('age') }}" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Estado de Suspensión -->
                <div>
                    <label for="is_suspended" class="block text-sm font-medium text-gray-700">Estado de Suspensión</label>
                    <select name="is_suspended" id="is_suspended" class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todos</option>
                        <option value="1" {{ request('is_suspended') == '1' ? 'selected' : '' }}>Suspendidos</option>
                        <option value="0" {{ request('is_suspended') == '0' ? 'selected' : '' }}>No suspendidos</option>
                    </select>
                </div>

                <!-- Botón de búsqueda -->
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-5 rounded-lg">Buscar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de usuarios -->
    @if($users->isEmpty())
        <p class="text-center text-gray-500">No hay usuarios registrados.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg border-collapse">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="py-3 px-4 text-white text-left">Nombre</th>
                        <th class="py-3 px-4 text-white text-left">Apellido Paterno</th>
                        <th class="py-3 px-4 text-white text-left">Apellido Materno</th>
                        <th class="py-3 px-4 text-white text-left">Fecha de Nacimiento</th>
                        <th class="py-3 px-4 text-white text-left">Género</th>
                        <th class="py-3 px-4 text-white text-left">Correo Electrónico</th>
                        <th class="py-3 px-4 text-white text-left">Suspendido</th>
                        <th class="py-3 px-4 text-white text-left">Rol</th>
                        <th class="py-3 px-4 text-white text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($users as $user)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ $user->name }}</td>
                            <td class="py-2 px-4">{{ $user->paternal }}</td>
                            <td class="py-2 px-4">{{ $user->maternal }}</td>
                            <!-- Validar si el campo birthdate no es nulo y formatearlo -->
                            <td class="py-2 px-4">
                                {{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('d/m/Y') : 'No disponible' }}
                            </td>
                            <td class="py-2 px-4">{{ $user->gender }}</td>
                            <td class="py-2 px-4">{{ $user->email }}</td>
                            <td class="py-2 px-4">{{ $user->is_suspended ? 'Sí' : 'No' }}</td>
                            <td class="py-2 px-4">{{ $user->role->name }}</td>
                            <td class="py-2 px-4 flex justify-between space-x-2">
                                <!-- Botón de suspensión -->
                                <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg">
                                        {{ $user->is_suspended ? 'Reactivar' : 'Suspender' }}
                                    </button>
                                </form>

                                <!-- Botón de editar -->
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">Modificar</a>

                                <!-- Botón de eliminar -->
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
