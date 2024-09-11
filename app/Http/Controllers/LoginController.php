<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\AccessRequest;

use App\Http\Controllers\EmailController;

use App\Http\Controllers\ResetPasswordController;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validación de entrada
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/', // Validación de mayúsculas, minúsculas y números
            //'password' => 'required|string|min:8',//poner validacion de minusculas y mayusculas y numeros
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buscar al usuario por correo
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Verificar si la cuenta está suspendida// redireccionar mandando un codigo a el correo junto con la opcion de 
            if ($user->is_suspended) {

                $controladoremail = app(EmailController::class);
                $emailResponse = $controladoremail->sendCodeViaEmail($request->email,"resetPassword");

                    // Verificar si la respuesta es válida y manejar errores
                if ($emailResponse['status'] == 1) {
                    //dd($emailResponse['message']);               
                    $parameter = $emailResponse['message'];
                    return redirect()->route('acount.suspended')
                    ->with(['message' =>$emailResponse['message']]);
                    //'Tu cuenta está suspendida, te hemos enviado un link de reestablecimiento de contraseña'
                    //revisar mensaje de si ya se mando el correo o de te mandamos el correo
                }else if ($emailResponse['status'] == 2) {
                    //dd($emailResponse['message']);               
                    return redirect()->route('acount.suspended')
                    ->with(['message' =>$emailResponse['message']]);
                }
            }

            // Verificar la contraseña
            if (Hash::check(base64_encode($request->password), $user->password)) {
                Auth::login($user, $request->filled('remember'));
                $request->session()->regenerate();

                //consulta de intento anterior para inhabilitarlo codigo nuevo de lina 54,61
                $today = Carbon::today();
                $accessRequest = AccessRequest::where('email', $request->email)
                    ->whereDate('date', $today)
                    ->where('valid', 1) 
                    ->first();
                
                if($accessRequest){//existe un registro
                    $accessRequest->valid = 0;//aqui ponemos que este registro de access ya no es valido
                    $accessRequest->save();
                }
                
                return redirect()->route('home');

            } else {//aqui se verifica sino hay un registro de intentos fallidos hoy
                
                //el registro que se busque de hoy tiene que tener un status de si ya se llego al limite
                //si ya se llego al limite de hoy busca el siguiente registro es decir el que no tenga el limite aun
                $today = Carbon::today();
                $accessRequest = AccessRequest::where('email', $request->email)
                    ->whereDate('date', $today)
                    ->where('valid', 1) 
                    ->first();

                    if ($accessRequest) {// Si ya existe un registro de hoy, incrementar los intentos
                        $accessRequest->attempts += 1;
                        $accessRequest->save();

                        if ($accessRequest->attempts >= 3) {//aqui es donde suspendemos la cuenta, buscamos la cuenta y cambiamos el is_suspended = 1
                            $accessRequest->valid = 0;//aqui ponemos que este registro de access ya no es valido
                            $accessRequest->save();
                            $user = User::where('email', $request->email);
                            $user->update(['is_suspended' => 1]);

                            $controladoremail = app(EmailController::class);
                            $emailResponse = $controladoremail->sendCodeViaEmail($request->email,"resetPassword");
                                // Verificar si la respuesta es válida y manejar errores
                            if ($emailResponse['status'] == 1) {
                                dd($emailResponse['message']);               
                                return redirect()->route('acount.suspended')
                                ->withErrors(['message' =>'Tu cuenta está suspendida, te hemos enviado un link de restablecimiento de contraseña']);
                 
                                //revisar mensaje de si ya se mando el correo o de te mandamos el correo
                            }else if ($emailResponse['status'] == 2) {
                                return redirect()->route('acount.suspended')
                                ->withErrors(['message' =>'Tu cuenta está suspendida, te hemos enviado un link de reestablecimiento de contraseña']);
                            }
                        }
                
                    } else {
                        // Si no existe un registro de hoy, crear uno nuevo
                        AccessRequest::create([
                            'email' => $request->email,
                            'attempts' => 1,
                            'ip_address' => $request->ip(),
                            'date' => now(),
                        ]);
                    }

                return redirect()->back()->withErrors(['password' => 'La contraseña es incorrecta.']);
            }
        } else {
            return redirect()->back()->withErrors(['email' => 'El correo electrónico no existe.']);
        }
    }
   
}
