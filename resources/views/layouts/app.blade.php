<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <!-- Incluir Bootstrap CSS desde CDN -->
     <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    @yield('head')
    <title>@yield('title')</title>
</head>
<body>
    <!--header y nav-->
    <!--un nav tiene que tener icono nombre y icono de user-->
    <!--un nav tiene que tener todas las acciones del profesor-->
   
    @yield('content')

    <footer>plantilla blade que va a contener la barra de navegacion y el menu lateral</footer>
    <!-- Incluir jQuery y Popper.js (necesario para Bootstrap JS) desde CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <!-- Incluir Bootstrap JS desde CDN -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>    
</body>
</html>