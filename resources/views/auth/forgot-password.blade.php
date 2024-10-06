@extends('layouts.app')
@section('content')

<!-- Contenedor Principal -->
<div class="flex flex-col min-h-screen justify-between bg-gray-100">
    <!-- Mensaje de sesión -->
    @if (session('message'))
        <div class="max-w-md mx-auto mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Errores de validación -->
    @if ($errors->has('message'))
        <div class="max-w-md mx-auto mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ $errors->first('message') }}
        </div>
    @endif

    <!-- Contenedor del formulario, centrado y con espacio adicional -->
    <div class="flex-grow flex items-center justify-center py-12">
        <div class="max-w-md w-full p-6 bg-white shadow-lg rounded-lg border border-gray-200">
            <h2 class="text-2xl font-bold text-center text-orange-600 mb-6">¿Olvidaste tu Contraseña?</h2>
            <p class="text-gray-600 text-center mb-4">Ingrese su correo electrónico para enviarle un código de recuperación.</p>

            <!-- Formulario de Envío de Código -->
            <form action="{{ route('password.send-password-code') }}" method="post" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-gray-800 text-sm font-medium mb-1">Correo Electrónico</label>
                    <input type="email" name="email" id="email" placeholder="Ingrese su correo" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                    Enviar Código
                </button>
            </form>
        </div>
    </div>


    
</div>

@endsection
