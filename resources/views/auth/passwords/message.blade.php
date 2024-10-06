@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-1/3 mx-auto">
        
        <!-- Mensajes de Error -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                    <svg class="fill-current h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 111.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 1.414L11.414 10l2.934 2.934a1 1 0 010 1.415z"/>
                    </svg>
                </span>
            </div>
        @endif

        <!-- Mensajes de Éxito -->
        @if (session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('message') }}
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                    <svg class="fill-current h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 111.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 1.414L11.414 10l2.934 2.934a1 1 0 010 1.415z"/>
                    </svg>
                </span>
            </div>
        @endif

        <!-- Mensaje de Cuenta Suspendida -->
        <h2 class="text-2xl font-bold text-center text-red-600 mb-6">Tu cuenta está suspendida</h2>
        <p class="text-gray-600 text-center mb-4">Para reactivar tu cuenta, envía un código de verificación a tu correo electrónico.</p>

        <!-- Formulario de Envío de Código -->
        <form action="{{ route('password.send-code') }}" method="post" class="space-y-4">
            @csrf
            <button type="submit" class="w-full bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Enviar Código
            </button>
        </form>
    </div>
</div>
@endsection
