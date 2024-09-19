<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script> <!-- Cargar Stripe.js -->
    <style>
        /* Estilos básicos de ejemplo para personalizar el formulario */
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input,
        .StripeElement {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .StripeElement {
            padding: 12px;
        }

        #card-element {
            margin-bottom: 20px;
        }

        #payment-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        #payment-button:hover {
            background-color: #45a049;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>

<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="payment-form" action="{{ route('stripe.payment') }}" method="POST">
        @csrf
        <!-- Campo para el nombre del titular de la tarjeta -->
        <div class="form-group">
            <label for="cardholder-name">Nombre del titular de la tarjeta</label>
            <input type="text" id="cardholder-name" name="cardholder-name" placeholder="Nombre del titular" required>
        </div>

        <!-- Campo para el correo electrónico -->
        <div class="form-group">
            <label for="payer-email">Correo electrónico</label>
            <input type="email" id="payer-email" name="payer-email" placeholder="Correo electrónico" required>
        </div>

        <!-- Elemento para mostrar el campo de la tarjeta -->
        <div class="form-group">
            <label for="card-element">Detalles de la tarjeta</label>
            <div id="card-element" class="StripeElement">
                <!-- Stripe Elements se insertará aquí -->
            </div>
        </div>

        <!-- Errores del elemento de tarjeta -->
        <div id="card-errors" role="alert"></div>

        <button type="submit" id="payment-button">Pagar</button>
    </form>
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

</body>
</html>
