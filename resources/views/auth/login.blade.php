@extends('layouts.app')
@section('title','Login')
@section('content')

<br>
<form method="POST" action="{{ route('signin') }}" class="max-w-md mx-auto p-6 bg-white shadow-lg rounded-lg border border-gray-200 dark:text-white dark:bg-gray-600 dark:border-gray-600">
    @csrf
    
    <!-- Título del formulario -->
    <h2 class="text-2xl font-bold text-center text-orange-600 mb-6">Iniciar Sesión</h2>
    
    <!-- Campo de email -->
    <div class="mb-4">
        <div class="relative">
            <input type="email" name="email" id="email" value="{{ old('email') }}" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
            <label for="email" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Correo</label>
        </div>
        @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Campo de contraseña -->
    <div class="mb-4">
        {{-- <label for="password" class="block text-gray-800 text-sm font-semibold mb-2">Contraseña</label>
        <input type="password" name="password" id="password" autocomplete="off" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"> --}}
        
        <div class="relative">
            <input type="password" name="password" id="password" autocomplete="off" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
            <label for="password" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Contraseña</label>
        </div>
        @error('password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Checkbox de mantener sesión -->
    <div class="flex items-center mb-4">
        <input type="checkbox" name="remember" id="remember" type="checkbox" value="" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Mantener sesión</label>
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
