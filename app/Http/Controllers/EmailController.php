<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\EmailVerification;
use App\Models\PasswordResetToken;
use Carbon\Carbon;

class EmailController extends Controller
{
    //pongo affairs porque es el asunto con el cual se manda el codigo y por lo cual aqui se hace la insersion en 
    //email verification resetPaswordtokens, o incluso para borrar la cuenta
    
    public function sendCodeViaEmail($email, $affair)
{
    $code = random_int(100000, 999999);
    $data = ['mailSubject', 'mailMessage', 'code'];
    $subject = null;

    switch ($affair) {
        case "verification":
            // Buscar si ya existe un código de verificación para este correo
            $existingVerification = EmailVerification::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->first();


            if ($existingVerification) {
                // Verificar si el código anterior ha expirado
                if (Carbon::now()->lt(Carbon::parse($existingVerification->expiration))) {
                    // Si el código no ha expirado, retornar un error
                    return [
                        'status' => false,
                        'message' => 'Aún no se puede enviar un nuevo código. El código anterior no ha expirado.'
                    ];
                }

                // Invalidar el código anterior si ha expirado
                $existingVerification->status = true;
                $existingVerification->save();
            }
            // Crear y enviar el nuevo código de verificación
            $subject = 'Código de Verificación';
            EmailVerification::create([
                'email' => $email,
                'token' => base64_encode($code),
                'expiration' => Carbon::now()->addMinutes(5)
            ]);

            Mail::send('emails.verification', [
                'mailSubject' => 'Código de Verificación',
                'mailMessage' => 'Tu código de verificación es:',
                'code' => $code
                ], function ($message) use ($email, $subject) {
                    $message->to($email)->subject($subject);
            });

            return [
                'status' => true,
                'message' => 'Código enviado correctamente.'
            ];

        break;  

        default:
            return [
                'status' => false,
                'message' => 'Tipo de asunto no reconocido.'
            ];
    }
}

    public function verifyCodeViaEmail($email,$code,$affair){
        
        switch($affair){
            case "verifyCodeRegister":
                /* 
                    Buscamos el registro de verificación y encriptamos el codigo para buscarlo en la DB
                    aqui va encriptar el codigo dado por el usuario
                */
                
                $code = base64_encode($code);
                
                $verification = EmailVerification::where('email', $email)->where('token', $code)->first();

                // 2. Verificar si se encontró el registro
                if (!$verification) {
                return 'Verificación no encontrada.';
                }

                // 3. Verificar si el token ya fue utilizado
                if ($verification->status) {
                    return 'El token es inválido.';
                }

                // 4. Verificar si el token ha expirado
                if ($verification->expiration < Carbon::now('America/Mexico_City')) {
                return 'El token ha expirado.';
                }

                // Si todas las verificaciones pasan, proceder con la verificación
                // Aquí puedes marcar el token como utilizado, etc.
                $verification->status = true;
                $verification->save();

                return 'Correo verificado con éxito.';

            break;            
        }

    }

}
