<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Incluir Bootstrap CSS desde CDN -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- menu aqui va-->
    @auth
    <a href="{{route('logout')}}">Salir</a>   
   @endauth
    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
    @endguest

    <div class="container">
        <h1 class="mb-4">Eventos</h1>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">
        @foreach ($events as $event)
            <div class="col">
                <div class="card">
                    @if ($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top" alt="image del event" style="height: 200px;">
                    @else
                        <!-- Puedes agregar una image por defecto o mensaje si no hay image -->
                        <div class="card-img-top text-center py-5">Sin image</div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->name }}</h5>
                        <p class="card-text">
                            <strong>Fecha:</strong> {{ $event->date }}<br>
                            <strong>Descripción:</strong> {{ $event->description }}<br>
                            @auth
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary">Ver más</a>
                            @endauth
                        </p>
                        <!-- Puedes agregar más detalles según sea necesario -->
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    </div>
    

    <!-- Incluir jQuery y Popper.js (necesario para Bootstrap JS) desde CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <!-- Incluir Bootstrap JS desde CDN -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
