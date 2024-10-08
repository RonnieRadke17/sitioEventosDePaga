@extends('layouts.app') 
@section('title','Registro')
@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg border border-gray-200">
        
        <!-- Mostrar errores de validación -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mostrar mensaje de error de sesión -->
        @if (session()->get('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session()->get('error') }}
            </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('process-register') }}">
            @csrf

            <!-- Título -->
            <h2 class="text-3xl font-bold text-center text-orange-600 mb-2">Registro de Usuario</h2>
            <p class="text-center text-gray-600 mb-6">Completa el formulario para crear tu cuenta</p>

            <!-- Contenedor de campos en dos columnas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-gray-800 text-sm font-medium mb-1">Nombre</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Apellido Paterno -->
                <div>
                    <label for="paternal" class="block text-gray-800 text-sm font-medium mb-1">Apellido Paterno</label>
                    <input id="paternal" type="text" name="paternal" value="{{ old('paternal') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
                    @error('paternal')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Apellido Materno -->
                <div>
                    <label for="maternal" class="block text-gray-800 text-sm font-medium mb-1">Apellido Materno</label>
                    <input id="maternal" type="text" name="maternal" value="{{ old('maternal') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
                    @error('maternal')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Fecha de nacimiento -->
                <div>
                    <label for="birthdate" class="block text-gray-800 text-sm font-medium mb-1">Fecha de Nacimiento</label>
                    <input id="birthdate" type="text" name="birthdate" value="{{ old('birthdate') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
                    @error('birthdate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Género -->
                <div class="col-span-1 md:col-span-2">
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
                <div class="col-span-1 md:col-span-2">
                    <label for="email" class="block text-gray-800 text-sm font-medium mb-1">Correo Electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="relative">
                    <label for="password" class="block text-gray-800 text-sm font-medium mb-1">Contraseña</label>
                    <input id="password" type="password" name="password" required class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
                    <!-- Ícono de Ojo -->
                    <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 px-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-500 hover:text-orange-500" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3 9c-5.522 0-10-4.477-10-10S6.478 2 12 2s10 4.477 10 10-4.478 10-10 10z" />
                        </svg>
                    </button>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="relative">
                    <label for="password_confirmation" class="block text-gray-800 text-sm font-medium mb-1">Confirmar Contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200">
                    <!-- Ícono de Ojo -->
                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 px-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-500 hover:text-orange-500" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3 9c-5.522 0-10-4.477-10-10S6.478 2 12 2s10 4.477 10 10-4.478 10-10 10z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Términos y Condiciones -->
            <div class="flex items-center mt-4 mb-6">
                <input id="terms" name="terms" type="checkbox" class="focus:ring-orange-500 h-4 w-4 text-orange-600 border-gray-300 rounded" required>
                <label for="terms" class="ml-2 text-sm font-medium text-gray-700">Acepto los <a href="#" class="text-orange-600 hover:text-orange-500">términos y condiciones</a>.</label>
                @error('terms')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botón de Registro -->
            <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-200">
                Registrar
            </button>
        </form>
    </div>
</div>

<!-- Enlace y script de Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#birthdate", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            locale: "es"
        });
    });

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
