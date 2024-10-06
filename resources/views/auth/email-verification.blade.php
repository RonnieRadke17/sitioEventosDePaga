@extends('layouts.app')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-1/3 mx-auto">

        <!-- Mensaje de sesión -->
        @if (session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none'">
                        <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 111.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 1.414L11.414 10l2.934 2.934a1 1 0 010 1.415z"/>
                    </svg>
                </span>
            </div>
        @endif

        <!-- Errores de validación -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none'">
                        <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 111.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 1.414L11.414 10l2.934 2.934a1 1 0 010 1.415z"/>
                    </svg>
                </span>
            </div>
        @endif

        <!-- Información del usuario y código -->
        @if(isset($remainingTimeFormatted))
            <p class="text-gray-600 mb-2">Tu código de verificación expira en <span class="font-bold">{{ $remainingTimeFormatted }}</span>.</p>
        @endif

        <p class="mb-2 text-gray-800">Hola, <span class="font-bold">{{ $user['name'] }}</span></p>
        <p class="text-gray-600">Te hemos enviado un código de verificación al correo:</p>
        <p class="mb-4 text-gray-800">Email: <span class="font-bold">{{ $user['email'] }}</span></p>

        <!-- Temporizador visual -->
        <div class="flex justify-center mb-4">
            <div id="timer" class="relative w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center shadow-inner">
                <span id="timerText" class="text-lg font-bold text-blue-500">5:00</span>
                <svg class="absolute top-0 left-0 w-full h-full">
                    <circle cx="50%" cy="50%" r="28" stroke="#e5e7eb" stroke-width="4" fill="none"></circle>
                    <circle id="countdownCircle" cx="50%" cy="50%" r="28" stroke="#3b82f6" stroke-width="4" fill="none" stroke-dasharray="176" stroke-dashoffset="176" class="transition-all duration-1000 ease-linear"></circle>
                </svg>
            </div>
        </div>

        <!-- Formulario de verificación -->
        <p class="mb-4 text-gray-600">Ingresa el código de verificación:</p>
        <form action="{{ route('check-email-verification') }}" method="POST" class="mb-4">
            @csrf
            <input type="number" name="code" placeholder="Código de verificación" class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="submit" value="Verificar" class="w-full px-3 py-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
        </form>

        <!-- Botón de reenvío de código -->
        <form action="{{ route('send-verification-code') }}" method="POST" id="resendForm">
            @csrf
            <button type="submit" class="w-full px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 focus:outline-none">Reenviar código</button>
        </form>

    </div>
</div>

<style>
    /* Ocultar las flechas en los campos de número */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>

<script>
    // Función para formatear el tiempo
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        if (seconds >= 60) {
            return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
        } else {
            return seconds;
        }
    }

    // Temporizador de cuenta regresiva
    let countdown = 300;
    const timerText = document.getElementById('timerText');
    const countdownCircle = document.getElementById('countdownCircle');
    const resendForm = document.getElementById('resendForm');
    const totalDuration = countdown;  // Duración total del temporizador en segundos

    const timerInterval = setInterval(() => {
        countdown--;
        timerText.textContent = formatTime(countdown);

        // Calcular el offset del círculo
        const offset = 176 - (176 * countdown) / totalDuration;
        countdownCircle.style.strokeDashoffset = offset;

        if (countdown <= 0) {
            clearInterval(timerInterval);
            resendForm.classList.remove('hidden');
            document.getElementById('timer').classList.add('hidden');
        }
    }, 1000);
</script>

@endsection
