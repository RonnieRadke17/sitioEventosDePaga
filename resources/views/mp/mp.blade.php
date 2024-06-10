<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>
    <div id="wallet_container"></div>

    <script>
        
        const mp = new MercadoPago("{{config('services.mercadopago.key')}}");
        const bricksBuilder = mp.bricks();

        
        mp.bricks().create("wallet", "wallet_container", {
        initialization: {
            preferenceId: "{{$preferenceId}}",
            redirectMode: "modal"
        },
        customization: {
        texts: {
        valueProp: 'smart_option',
        },
        },
        });

    </script>
</body>
</html>