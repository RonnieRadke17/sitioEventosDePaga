<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\EmailVerification;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Crypt;//se usa para la encriptacion
use Illuminate\Support\Str;//se usa para generar texto aleatorio
use Carbon\Carbon;//se usa para la fecha
use App\Http\Controllers\ErrorsController;
use Illuminate\Support\Facades\Validator;//se usa para validar informacion

class ResetPasswordController extends Controller
{
    //showFormSendCode
    public function showFormSendCode()
    {
        return view('auth.forgot-password');
    }
    //falta poner algo que verifique que no se pueda mandar otro codigo de reguridad para no mandar muchos a la vez
    public function sendPasswordCode(Request $request){
        $email = $request->email;

        //revisar que el correo si este en el sistema
        $emailUser = User::where('email',$email)->exists();
        
        // El usuario con el correo electrónico existe
        if ($emailUser) {
            
            // Buscar si ya existe un código de verificación para este correo
            $existingVerification = PasswordResetToken::where('email', $email)->first();

            if ($existingVerification) {
                // Invalidar el código anterior
                $existingVerification->status = true;
                $existingVerification->save();
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
            
            return redirect()->back()->with('message','Link enviado a su correo');

        } else {
            // No hay ningún usuario con ese correo electrónico
            return redirect()->back()->withErrors(['message' => 'Correo no válido']);
        }

    }

    public function showResetForm($token)
    {
        $passwordReset = PasswordResetToken::where('token', $token)->first();
        
        if (!$passwordReset || $passwordReset->expiration <= Carbon::now()) {//si el codigo no fue encontrado o si paso su tiempo de expiracion
            
            //redireccionamos a la ruta del controlador que contiene las vistas con error debido a que en este caso no se puede volver a intentar cargar la pagina
            return redirect()->route('window.error', ['message' => 'código expirado']);
        
        }else if($passwordReset->status == 1){//si el codigo ya fue usado    

            return redirect()->route('window.error', ['message' => 'código ya usado']);
        }
        //falta revisar si el codigo ya fue usado y una ventana de si el codigo es invalido(tengo mis dudas en el ultimo)
        return view('auth.passwords.reset', ['token' => $token]);
    }

    
    //revisar el token del url y revisar el token del form verificando que sean veridicos
    public function reset(Request $request)//falta validar la informacion
    {
        /* 
            Aqui validamos la contrasena, que sea igual, que sea de min 8 caracteres y max de 40
        */
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|max:40|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        /* 
            si la contrasena es valida entonces buscamos el token en la DB para actualizar la contrasena 
            del correo el cual esta con el token e inhabilitamos el token y logueamos al usuario
        */
        $passwordReset = PasswordResetToken::where('token', $request->token)->first();

        if (!$passwordReset || $passwordReset->expiration < Carbon::now()) {
            return back()->withErrors(['token' => 'El token es inválido o ha expirado.']);
        }

        //aqui buscamos el usuario con ese correo
        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No se ha encontrado ningún usuario con este correo.']);
        }
        /* 
            Aqui encriptamos la contrasena del usuario por el metodo convencional de laravel
        */
        $user->password = base64_encode($request->password);
        //$user->password = Crypt::encryptString($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Inhabilitar el token utilizado
        $passwordReset->status = true; 
        $passwordReset->save();

        auth()->login($user);

        return redirect()->route('home');// redijirimos a la ruta principal

    }
}
