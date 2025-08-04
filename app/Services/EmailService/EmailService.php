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
        $data = ['mailSubject', 'mailMessage', 'code'];
        $subject = null;

            /* buscamos en los registros incluso usando el withtrash y e incluso usamos el type*/
            $register = UserToken::where('email', $email)
                ->where('type', $type) // parámetro adicional
                ->orderBy('created_at', 'desc')
                ->first();

                /* revisar condicional de si es valido todavia */
                if ($register && $register->created_at && $register->created_at->gt(now()->subMinutes(5))) {
                    // código si aún es válido
                } else {
                    // código si ya expiró o no existe
                }

                if ($reqister) {
                    // Verificar si el código anterior ha expirado
                    if (1 == 1) {
                        // Si el código no ha expirado, retornar un error
                        return [
                            'status' => false,
                            'message' => 'Aún no se puede enviar un nuevo código. El código anterior no ha expirado.'
                        ];
                    }
                }
            
            // Crear nuevo código de verificación
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


    }






    /* refactorización para  poner verificación y reset*/
   public function send∫($email, $affair)
    {
        $code = random_int(100000, 999999);
        $data = ['mailSubject', 'mailMessage', 'code'];
        $subject = null;

        switch ($affair) {
            case "verification":
                // Buscar si ya existe un código de verificación para este correo
                /* falta poner el type de tipo verification*/
                $existingVerification = UserToken::where('email', $email)->orderBy('created_at', 'desc')->first();

                if ($existingVerification) {
                    // Verificar si el código anterior ha expirado
                    if (Carbon::now()->lt(Carbon::parse($existingVerification->expiration))) {
                        // Si el código no ha expirado, retornar un error
                        return [
                            'status' => false,
                            'message' => 'Aún no se puede enviar un nuevo código. El código anterior no ha expirado.'
                        ];
                    }
                }

                // Crear nuevo código de verificación
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

            case "resetPassword"://$email = $request->email;
                
                //revisar que el correo si este en el sistema
                $emailUser = User::where('email',$email)->exists();
                
                // El usuario con el correo electrónico existe
                if ($emailUser) {
                    // Buscar si ya existe un código de verificación para este correo
                    $existingVerification = PasswordResetToken::where('email', $email)
                    ->orderBy('created_at', 'desc')
                    ->first();
        
                    if ($existingVerification) {
        
                        // Invalidar el código anterior si ha expirado
                        $existingVerification->status = true;
                        $existingVerification->save();

                        // Verificar si el código anterior ha expirado
                        if (Carbon::now()->lt(Carbon::parse($existingVerification->expiration))) {
                            // Si el código no ha expirado, retornar un error
                            return [
                                'status' => 1,
                                'message' => 'Aún no se puede enviar un nuevo código. El código anterior no ha expirado.'
                            ];
                            //return redirect()->back()->withErrors(['message' => 'Aún no se puede enviar un nuevo código. El código anterior no ha expirado']);
                        }

                    }
        
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
                    //aqui en el login se manda mensaje distinto debido a que es por cuenta bloqueada
                    return [
                        'status' => 2,
                        'message' => 'Link enviado a su correo'
                    ];
                    
                } else {
                    // No hay ningún usuario con ese correo electrónico
                    return [
                        'status' => 3,
                        'message' => 'Correo no válido'
                    ];
                    //return redirect()->back()->withErrors(['message' => 'Correo no válido']);
                }
            
            break;

            default:
                return [
                    'status' => false,
                    'message' => 'Tipo de asunto no reconocido.'
                ];
        }
    }


    
}