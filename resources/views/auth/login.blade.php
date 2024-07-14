<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <main class="container aling-center p-5">
        <form method="Post" action="{{route('signin')}}">
        @csrf
        
        <label for="email">Email address</label>
        <input type="email" name="email" required autocomplete="disable">
        
        <label for="password">Password</label>
        <input type="password" name="password" required autocomplete="disable">
        
        <input type="checkbox" name="remember">
        <label for="remember">Mantener sesion</label>

        <p><a href="{{route('register')}}">¿Olvidaste tu contraseña?</a></p>
        <p>No tienes cuenta? <a href="{{route('register')}}">Registrate</a></p>
        <button type="submit">Acceder</button>
            
        </form>
    </main>
</body>
</html>