@extends('layouts.app') 
@section('title','Registro')
@section('content')

<!-- Mostrar errores de validación -->
@if ($errors->any())
    <div class="max-w-md mx-auto mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Mostrar mensaje de error de sesión -->
@if (session()->get('error'))
    <div class="max-w-md mx-auto mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
        {{ session()->get('error') }}
    </div>
@endif

<form method="POST" action="{{ route('process-register') }}" class="max-w-lg mx-auto p-6 bg-white shadow-lg rounded-lg border border-gray-200">
    @csrf
    
    <!-- Título del formulario -->
    <h2 class="text-2xl font-bold text-center text-orange-600 mb-6">Registro de Usuario</h2>

    <!-- Nombre -->
    <div class="mb-4">
        <label for="name" class="block text-gray-800 text-sm font-medium mb-1">Nombre</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
        @error('name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Apellido Paterno -->
    <div class="mb-4">
        <label for="paternal" class="block text-gray-800 text-sm font-medium mb-1">Apellido Paterno</label>
        <input id="paternal" type="text" name="paternal" value="{{ old('paternal') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
        @error('paternal')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Apellido Materno -->
    <div class="mb-4">
        <label for="maternal" class="block text-gray-800 text-sm font-medium mb-1">Apellido Materno</label>
        <input id="maternal" type="text" name="maternal" value="{{ old('maternal') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
        @error('maternal')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Fecha de nacimiento -->
    <div class="mb-4">
        <label for="birthdate" class="block text-gray-800 text-sm font-medium mb-1">Fecha de Nacimiento</label>
        <input id="birthdate" type="date" name="birthdate" value="{{ old('birthdate') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
        @error('birthdate')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Género -->
    <div class="mb-4">
        <label class="block text-gray-800 text-sm font-medium mb-1">Género</label>
        <div class="flex items-center">
            <input type="radio" name="gender" id="male" value="M" class="mr-2 text-orange-600 focus:ring-orange-500 border-gray-300 rounded" {{ old('gender') == 'M' ? 'checked' : '' }}>
            <label for="male" class="mr-4">Masculino</label>

            <input type="radio" name="gender" id="female" value="F" class="mr-2 text-orange-600 focus:ring-orange-500 border-gray-300 rounded" {{ old('gender') == 'F' ? 'checked' : '' }}>
            <label for="female">Femenino</label>
        </div>
        @error('gender')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Correo Electrónico -->
    <div class="mb-4">
        <label for="email" class="block text-gray-800 text-sm font-medium mb-1">Correo Electrónico</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
        @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Campo de Contraseña con Icono de Ojo -->
    <div class="mb-4 relative">
        <label for="password" class="block text-gray-800 text-sm font-medium mb-1">Contraseña</label>
        <input id="password" type="password" name="password" required class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
        <!-- Botón de Icono de Ojo con mejor diseño -->
        <button type="button" class="absolute inset-y-0 right-0 flex items-center px-2 h-full" onclick="togglePassword('password')">
            <svg class="h-5 w-5 text-gray-500 hover:text-orange-500 transition-colors duration-200" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm0 0c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3zm-3 0c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z" />
            </svg>
        </button>
        @error('password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Campo de Confirmar Contraseña con Icono de Ojo -->
    <div class="mb-6 relative">
        <label for="password_confirmation" class="block text-gray-800 text-sm font-medium mb-1">Confirmar Contraseña</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
        <!-- Botón de Icono de Ojo con mejor diseño -->
        <button type="button" class="absolute inset-y-0 right-0 flex items-center px-2 h-full" onclick="togglePassword('password_confirmation')">
            <svg class="h-5 w-5 text-gray-500 hover:text-orange-500 transition-colors duration-200" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm0 0c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3zm-3 0c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z" />
            </svg>
        </button>
    </div>

    <!-- Aceptación de Términos y Condiciones -->
    <div class="mb-6">
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input id="terms" name="terms" type="checkbox" class="focus:ring-orange-500 h-4 w-4 text-orange-600 border-gray-300 rounded" required>
            </div>
            <div class="ml-3 text-sm">
                <label for="terms" class="font-medium text-gray-700">Acepto los <a href="#" class="text-orange-600 hover:text-orange-500">términos y condiciones</a>.</label>
            </div>
        </div>
        @error('terms')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Botón de Registro -->
    <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
        Registrar
    </button>
</form>

<script>
    // Función para mostrar/ocultar la contraseña
    function togglePassword(fieldId) {
        var field = document.getElementById(fieldId);
        if (field.type === "password") {
            field.type = "text";
        } else {
            field.type = "password";
        }
    }
</script>

@endsection
