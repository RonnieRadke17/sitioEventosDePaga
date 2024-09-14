<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\EmailVerification;
use App\Models\PasswordResetToken;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;//se usa para la encriptacion
use Illuminate\Support\Str;//se usa para generar texto aleatorio

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


    //revisar el token del url y revisar el token del form verificando que sean veridicos
    public function reset(Request $request)//falta validar la informacion
    {
        $passwordReset = PasswordResetToken::where('token', $request->token)->first();

        if (!$passwordReset || $passwordReset->expiration < Carbon::now()) {
            return [
                'status' => 1,
                'message' => 'El token es inválido o ha expirado.'
            ];
            //return back()->withErrors(['token' => 'El token es inválido o ha expirado.']);
        }

        //aqui buscamos el usuario con ese correo
        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {
            return [
                'status' => 2,
                'message' => 'No se ha encontrado ningún usuario con este correo.'
            ];
            //return back()->withErrors(['email' => 'No se ha encontrado ningún usuario con este correo.']);
        }
        /* 
            Aqui encriptamos la contrasena del usuario por el metodo convencional de laravel y habilitamos la cuenta en dado caso de que este suspendida
        */
        if($user->is_suspended){
            $user->is_suspended = 0;//habilitamos la cuenta de nuevo
        }
        
        $user->password = base64_encode($request->password);
        //$user->password = Crypt::encryptString($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Inhabilitar el token utilizado
        $passwordReset->status = true; 
        $passwordReset->save();

        auth()->login($user);

        //return redirect()->route('home');// redijirimos a la ruta principal
        return [
            'status' => 3,
        ];
    }

}
