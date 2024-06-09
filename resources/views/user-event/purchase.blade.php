<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel - PayPal Integration</title>
</head>
<body>
    <h2>Product:</h2>
    <h5 class="card-title">{{ $event->name }}</h5>
    <h5 class="card-title">{{ $event->description }}</h5>
    <h3>Price:</h3>
    <h5 class="card-title">{{ $event->price }}</h5>
    boton de pago de 3 metodos
    y si se paga regresa a la pagina de todos los eventos con una alerta de evento pagado o compra cancelada
    
    

    <form action="{{ route('paypal') }}" method="post">
        @csrf
        <input type="hidden" name="price" value="{{ $event->price }}">
        <input type="hidden" name="product_name" value="{{ $event->name }}">
        <input type="hidden" name="event" value="{{ $event->id }}">
        <input type="hidden" name="quantity" value="1">
        <button type="submit">Pay with payPal</button>
    </form>
    

    <form action="{{ route('paypal') }}" method="post">
        @csrf
        <input type="hidden" name="price" value="5">
        <input type="hidden" name="product_name" value="Laptop">
        <input type="hidden" name="quantity" value="1">
        <button type="submit">Pay with MP</button>
    </form>

</body>
</html>