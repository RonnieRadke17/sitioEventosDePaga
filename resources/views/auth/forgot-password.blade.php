@extends('layouts.app')
@section('content')

<!-- Contenedor Principal -->
<div class="flex flex-col min-h-screen justify-between">
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
    <div class="flex-grow flex items-center justify-center py-7">
        <div class="max-w-md w-full p-6 shadow-lg rounded-lg border border-gray-200 dark:text-white dark:bg-gray-600 dark:border-gray-600">
            <h2 class="text-2xl font-bold text-center text-orange-600 mb-6 dark:text-white">¿Olvidaste tu Contraseña?</h2>
            <p class="text-gray-600 text-center mb-4  dark:text-white">Ingrese su correo electrónico para enviarle un código de recuperación.</p>

            <!-- Formulario de Envío de Código -->
            <form action="{{ route('password.send-password-code') }}" method="post" class="space-y-4">
                @csrf
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
                <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                    Enviar Código
                </button>
            </form>
        </div>
    </div>


    
</div>
@endsection
