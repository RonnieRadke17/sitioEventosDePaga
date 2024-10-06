<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Restablecimiento de Contraseña</title>
    <style>
        /* Estilos generales para el correo */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            width: 100%;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #dddddd;
        }

        .email-header {
            background-color: #FF5722;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }

        .email-body p {
            margin: 10px 0;
        }

        .email-footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777777;
        }

        .btn {
    display: inline-block;
    padding: 12px 20px;
    margin-top: 20px;
    color: #000000; /* Color de letras negro */
    background-color: #FF5722; /* Fondo naranja */
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold; /* Letra en negrita */
    text-align: center;
}


        .btn:hover {
            background-color: #e64a19;
        }

        .email-footer a {
            color: #FF5722;
            text-decoration: none;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Encabezado del correo -->
        <div class="email-header">
            <h1>Restablecimiento de Contraseña</h1>
        </div>

        <!-- Cuerpo del correo -->
        <div class="email-body">
            <p>Hola,</p>
            <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta. Para continuar con el proceso, haz clic en el siguiente enlace:</p>
            <p style="text-align: center;">
                <a href="{{ $url }}" class="btn">Restablecer Contraseña</a>
            </p>
            <p>Si no realizaste esta solicitud, puedes ignorar este correo de manera segura. Este enlace expirará en 5 minutos.</p>
            <p>¡Gracias!</p>
            <p>El equipo de Eventos Deportivos</p>
        </div>

        <!-- Pie del correo -->
        <div class="email-footer">
            <p>Si tienes algún problema, contáctanos en <a href="mailto:soporte@eventosdeportivos.com">soporte@eventosdeportivos.com</a></p>
        </div>
    </div>
</body>
</html>
