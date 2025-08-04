<?php
namespace App\Services\EmailService;
use App\Models\UserToken;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Carbon\Carbon;
use App\Models\User;

use Illuminate\Support\Str;//se usa para generar texto aleatorio



class EmailService{

    public function SendCode($email,$type){
        $code = random_int(100000, 999999);
        //$data = ['mailSubject', 'mailMessage', 'code'];
        $subject = null;

            /* en caso de ser restablecimiento se revisa que exista el usuario en el sistema*/
            if ($type === 'resetPassword') {
                $emailUser = User::where('email', $email)->exists();
                if(!$emailUser){//si el usuario no existe
                    return [
                        'status' => false,
                        'message' => 'El usuario no existe'
                    ];
                }
            }

            /* buscamos en los registros incluso usando el withtrash y e incluso usamos el type*/
        $register = UserToken::where('email', $email)
            ->where('type', $type) // parámetro adicional
            ->orderBy('created_at', 'desc')
            ->first();

            /* verificamos el si se puede mandar otro código */
            if ($register && $register->created_at && $register->created_at->gt(now()->subMinutes(5))) {
                return [
                    'status' => false,
                    'message' => 'Aún no se puede enviar un nuevo código. El código anterior no ha expirado.'
                ];
            }
        
            switch($type){
                case "email_verification": 
                    $subject = 'Código de Verificación';
                    UserToken::create([
                        'email' => $email,
                        'token' => base64_encode($code),
                        'type' => 'email_verification',
                        'expiration' => Carbon::now()->addMinutes(5)
                    ]);

                    /* mandar el correo */
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

                case "resetPassword":
                    $tokenEncrypted = Crypt::encryptString(Str::random(10));// generamos el token y lo encriptamos
        
                    PasswordResetToken::create([
                        'email' => $email,
                        'token' => $tokenEncrypted,
                        'expiration' => Carbon::now()->addMinutes(5)
                    ]);
                    // Enviar correo con el enlace de restablecimiento
                    $subject = 'Restablecimiento de Contraseña';
                    $data = [
                        'url' => url('/password/reset', $tokenEncrypted)
                    ];
        
                    Mail::send('emails.password_reset', $data, function ($message) use ($email, $subject) {
                        $message->to($email)->subject($subject);
                    });

                    return [
                        'status' => true,
                        'message' => 'Te hemos enviado un link a tu correo para proceder'
                    ];
                    break;

                default:
                    return [
                        'status' => false,
                        'message' => 'Error interno del sitio'
                    ];
            }       
    }


    
}