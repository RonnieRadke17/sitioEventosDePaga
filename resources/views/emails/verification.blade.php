<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Código</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <!-- Header -->
        <div style="background-color: #ef4444; padding: 20px; text-align: center; color: #ffffff;">
            <h1 style="font-size: 24px; font-weight: bold; margin: 0;">{{ $mailSubject }}</h1>
        </div>

        <!-- Body -->
        <div style="padding: 20px; color: #374151;">
            <p style="font-size: 16px; margin-bottom: 20px;">
                {{ $mailMessage }}
            </p>

            <div style="text-align: center; margin: 20px 0;">
                <span style="display: inline-block; font-size: 32px; font-weight: bold; color: #ef4444; letter-spacing: 4px; padding: 10px 20px; background-color: #f9fafb; border: 1px solid #ef4444; border-radius: 5px;">
                    {{ $code }}
                </span>
            </div>

            <p style="font-size: 14px; color: #6b7280; text-align: center; margin-top: 20px;">
                Si no solicitaste este código, por favor ignora este mensaje.
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f3f4f6; padding: 10px 20px; text-align: center; color: #6b7280; font-size: 12px;">
            <p style="margin: 0;">
                © {{ date('Y') }} Eventos Deportivos. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
