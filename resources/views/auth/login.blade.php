@extends('layouts.app')
@section('title','Login')
@section('content')



<form method="POST" action="{{ route('signin') }}" class="max-w-md mx-auto p-6 bg-white shadow-md rounded-lg">
    @csrf
    
    <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email address</label>
        <input type="email" name="email" id="email" required autocomplete="off" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('email')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    
    <div class="mb-4">
        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
        <input type="password" name="password" id="password" required autocomplete="off" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('password')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    
    <div class="flex items-center mb-4">
        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
        <label for="remember" class="ml-2 block text-gray-700 text-sm font-medium">Mantener sesión</label>
    </div>
    
    <div class="mb-4 text-sm text-gray-600">
        <p><a href="{{ route('forgot-password') }}" class="text-indigo-600 hover:text-indigo-700">¿Olvidaste tu contraseña?</a></p>
        <p>No tienes cuenta? <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700">Regístrate</a></p>
    </div>
    
    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Acceder</button>
</form>

@endsection
