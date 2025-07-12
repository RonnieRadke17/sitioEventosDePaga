<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pago con MercadoPago</title>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>
    <h1>Realizar pago con MercadoPago</h1>

    <div id="wallet_container"></div>

    <script>
        const mp = new MercadoPago("{{ config('services.mercadopago.key') }}");
        const bricksBuilder = mp.bricks();

        mp.bricks().create("wallet", "wallet_container", {
            initialization: {
                preferenceId: "{{ $preferenceId }}",
                redirectMode: "modal"
            },
            customization: {
                texts: {
                    valueProp: 'Paga con la mejor opción disponible',
                },
            },
            onError: function(error) {
                console.error('Error al crear el Brick de MercadoPago:', error);
            }
        });
    </script>
</body>
</html>
