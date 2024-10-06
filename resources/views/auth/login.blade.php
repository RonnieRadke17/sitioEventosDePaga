@extends('layouts.app')
@section('title','Login')
@section('content')

<br>
<form method="POST" action="{{ route('signin') }}" class="max-w-md mx-auto p-6 bg-white shadow-lg rounded-lg border border-gray-200">
    @csrf
    
    <!-- Título del formulario -->
    <h2 class="text-2xl font-bold text-center text-orange-600 mb-6">Iniciar Sesión</h2>
    
    <!-- Campo de email -->
    <div class="mb-4">
        <label for="email" class="block text-gray-800 text-sm font-semibold mb-2">Correo Electrónico</label>
        <input type="email" name="email" id="email" autocomplete="off" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" value="{{ old('email') }}">
        @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Campo de contraseña -->
    <div class="mb-4">
        <label for="password" class="block text-gray-800 text-sm font-semibold mb-2">Contraseña</label>
        <input type="password" name="password" id="password" autocomplete="off" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
        @error('password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Checkbox de mantener sesión -->
    <div class="flex items-center mb-4">
        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
        <label for="remember" class="ml-2 block text-gray-700 text-sm">Mantener sesión</label>
    </div>
    
    <!-- Enlaces de ayuda y registro -->
    <div class="mb-4 text-sm text-gray-600">
        <p><a href="{{ route('forgot-password') }}" class="text-orange-600 hover:text-orange-700">¿Olvidaste tu contraseña?</a></p>
        <p>No tienes cuenta? <a href="{{ route('register') }}" class="text-orange-600 hover:text-orange-700">Regístrate</a></p>
    </div>
    
    <!-- Botón de acceso -->
    <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
        Acceder
    </button>
</form>

@endsection
