<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/navbar.css">
    <script src="{{ asset('js/navbar.js') }}"></script> 
    @yield('head')
    <title>@yield('title')</title>
</head>
<body>
    <!--header y nav-->
    <!--un nav tiene que tener icono nombre y icono de user-->
    <!--un nav tiene que tener todas las acciones del profesor-->
   
    @yield('content')

    <footer>plantilla blade que va a contener la barra de navegacion y el menu lateral</footer>
    
</body>
</html>