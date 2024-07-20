<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\EmailVerification;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\ErrorsController;

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

            // Crear un nuevo token de restablecimiento de contraseña
            $tokenEncrypted = Hash::make(Str::random(60)); // Generar y encriptar el token

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
        
        //si el codigo no fue encontrado o si paso su tiempo de expiracion
        if (!$passwordReset || $passwordReset->expiration <= Carbon::now()) {
            //redireccionamos a la ruta del controlador que contiene las vistas con error debido a que en este caso no se puede volver a intentar cargar la pagina
            return redirect()->route('window.error', ['message' => 'código expirado']);
        
        //si el codigo ya ha expirado    
        }else if($passwordReset->status == 1){

            return redirect()->route('window.error', ['message' => 'código ya usado']);
        }
        //falta revisar si el codigo ya fue usado y una ventana de si el codigo es invalido(tengo mis dudas en el ultimo)
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function reset(Request $request)//falta validar la informacion
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $passwordReset = PasswordResetToken::where('token', $request->token)->first();

        if (!$passwordReset || $passwordReset->expiration < Carbon::now()) {
            return back()->withErrors(['token' => 'El token es inválido o ha expirado.']);
        }

        $user = User::where('email', $passwordReset->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'No se ha encontrado ningún usuario con este correo.']);
        }

        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Eliminar el token utilizado
        //$passwordReset->delete();

        // Inhabilitar el token utilizado
        $passwordReset->status = false; // O cualquier otro valor que indique que el token ya no es válido
        $passwordReset->save();

        auth()->login($user);

        return redirect('/home');
    }
}
