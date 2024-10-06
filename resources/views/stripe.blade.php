@extends('layouts.app')

@section('title', 'Pago con Stripe')

@section('head')
    <!-- Cargar Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script> 
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados para mejorar la experiencia de usuario */
        .StripeElement {
            background-color: #f8f9fa;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            transition: border-color 0.2s ease-in-out;
        }

        .StripeElement:focus {
            border-color: #6366f1;
        }

        #payment-button {
            background-color: #6366f1;
            transition: background-color 0.2s ease-in-out;
        }

        #payment-button:hover {
            background-color: #4f46e5;
        }

        /* Mejorar el espaciado general */
        .form-group {
            margin-bottom: 1.5rem; /* Incrementar el margen inferior para más espacio */
        }
    </style>
@endsection

@section('content')
<div class="container mx-auto py-12 flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg bg-white p-8 rounded-lg shadow-lg">
        

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Realiza tu pago</h2>
        
        <form id="payment-form" action="{{ route('stripe.payment') }}" method="POST" class="space-y-6">
            @csrf
            <!-- Campo para el nombre del titular de la tarjeta -->
            <div class="form-group">
                <label for="cardholder-name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del titular de la tarjeta</label>
                <input type="text" id="cardholdername" name="name" placeholder="Nombre del titular" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Campo para el correo electrónico -->
            <div class="form-group">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                <input type="email" id="payer-email" name="email" placeholder="Correo electrónico" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Elemento para mostrar el campo de la tarjeta -->
            <div class="form-group">
                <label for="card-element" class="block text-sm font-medium text-gray-700 mb-1">Detalles de la tarjeta</label>
                <div id="card-element" class="StripeElement mt-1 block w-full p-3"></div>
            </div>

            <!-- Errores del elemento de tarjeta -->
            <div id="card-errors" role="alert" class="text-red-600 text-sm mt-2"></div>

            <button type="submit" id="payment-button"
                class="w-full py-4 px-4 border border-transparent rounded-md shadow-sm text-white font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Pagar
            </button>
        </form>
    </div>
</div>

<script type="text/javascript">
    // Inicializar Stripe
    var stripe = Stripe('{{ env("STRIPE_KEY") }}');
    var elements = stripe.elements();

    // Personalizar estilo de los elementos de Stripe
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    // Crear el elemento de la tarjeta y montarlo en el div #card-element
    var card = elements.create('card', { style: style });
    card.mount('#card-element');

    // Manejar los errores de validación en tiempo real
    card.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Manejar la presentación del formulario
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Mostrar los errores en el formulario
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Enviar el token al servidor
                stripeTokenHandler(result.token);
            }
        });
    });

    // Insertar el token de Stripe en el formulario y enviarlo al servidor
    function stripeTokenHandler(token) {
        // Crear un campo input oculto con el token
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Enviar el formulario con el token y el correo
        form.submit();
    }
</script>



@endsection
