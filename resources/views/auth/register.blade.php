<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <title>Registro</title>
</head>
<body>
    <main class="container aling-center p-5">
        <form method="Post" action="{{route('signup')}}">
        @csrf
        <label for="name">Name</label>
        <input type="text" name="name" required autocomplete="disable">
        <br>
        <label for="paternal">Paternal</label>
        <input type="text" name="paternal" required autocomplete="disable">
        <br>
        <label for="maternal">Maternal</label>
        <input type="text" name="maternal" required autocomplete="disable">
        <br>
        <label for="age">age</label>
        <input type="text" name="age" required autocomplete="disable">
        <br>
        <label for="email">Email address</label>
        <input type="email" name="email" required autocomplete="disable">
        <br>
        <label for="password">Password</label>
        <input type="password" name="password" required autocomplete="disable">
        <br>
        <input type="checkbox" name="student">
        <label for="student">Soy estudiante UPP</label>
        <br>
        <button type="submit">Registrarse</button>
            
        </form>
    </main>
</body>
</html>