<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restablecer Contraseña</title>
</head>
<body>
    
<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label for="password">Nueva contraseña</label>
        <input type="password" name="password" required>
    </div>

    <div>
        <label for="password_confirmation">Confirmar nueva contraseña</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <div>
        <button type="submit">Restablecer contraseña</button>
    </div>
</form>

</body>
</html>
