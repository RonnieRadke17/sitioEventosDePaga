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
use App\Http\Controllers\EmailController;

class ResetPasswordController extends Controller
{
    //showFormSendCode
    public function showFormSendCode()
    {
        return view('auth.forgot-password');
    }

    public function sendPasswordCode(Request $request){//revisar el Request $request para ver si se recibe todo o solo el correo, falla el restablecer
        $email = $request->email;
        // Llamar al controlador de correo para enviar el código de verificación
        $controladoremail = app(EmailController::class);
        $emailResponse = $controladoremail->sendCodeViaEmail($request->email, "resetPassword");

        if($emailResponse['status'] == 1){
            return redirect()->back()->withErrors(['message' => $emailResponse['message']]);
        
        }else if($emailResponse['status'] == 2){
            return redirect()->back()->with('message',$emailResponse['message']);
            //return redirect()->back()->withErrors(['message' => $emailResponse['message']]);
        }else if($emailResponse['status'] == 3){
            return redirect()->back()->withErrors(['message' => $emailResponse['message']]);
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

    
    
    public function reset(Request $request)//falta validar la informacion
    {
        /* 
            Aqui validamos la contrasena, que sea igual, que sea de min 8 caracteres y max de 40
        */
        $validator = Validator::make($request->all(), [//'password' => 'required|string|min:8|max:40|confirmed',
            'password' => 'required|string|min:8|max:40|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/', // Validación de mayúsculas, minúsculas y números
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
        //aqui mandamos a llamar el metodo ya con los datos validados
        // Llamar al controlador de correo para enviar el código de verificación
        $controladoremail = app(EmailController::class);
        $emailResponse = $controladoremail->reset($request);

            // Verificar si la respuesta es válida y manejar errores
        if ($emailResponse['status'] == 1) {
            return back()->withErrors(['token' => $emailResponse['message']]);

        }else if ($emailResponse['status'] == 2) {
            return back()->withErrors(['token' => $emailResponse['message']]);

        }else if ($emailResponse['status'] == 3) {
            return redirect()->route('home');
        }
    }



    public function reSendPasswordCode(){//revisar el Request $request para ver si se recibe todo o solo el correo, falla el restablecer
        //el correo en este caso se obtiene de un session que se declara en login controller para redireccionar a
        //este metodo
        $email = session('email');
        //$email = $request->email;
        // Llamar al controlador de correo para enviar el código de verificación
        //falta el caso de que si se halla mandado a correo
        $controladoremail = app(EmailController::class);
        $emailResponse = $controladoremail->sendCodeViaEmail($email, "resetPassword");

        if($emailResponse['status'] == 1){
            return redirect()->back()->withErrors(['error' => $emailResponse['message']]);
        
        }else if($emailResponse['status'] == 2){//correo enviado
            return redirect()->back()->with('message',$emailResponse['message']);
        }else if($emailResponse['status'] == 3){
            return redirect()->back()->withErrors(['error' => $emailResponse['message']]);
        }
    }






}

