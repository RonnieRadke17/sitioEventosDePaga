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

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validación de entrada
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/', // Validación de mayúsculas, minúsculas y números
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
                //aqui se redirecciona para mandar un link de cambio de contrasena el cual se puede mandar cuandoe el link anterior caduca
                //mandar correo de restablecimiento a el correo suspendido
                //redireccionar a una vista la cual muestre el mensaje de cuenta suspendida, link mandado por correo
                //y un contado atras del tiempo restante, para eso hacer una consulta del tiempo restante
                //y cuando se acabe el tiempo que tenga la opcion de volver a mandar el codigo en caso de que la cuenta siga bloqueada
                //return redirect()->route('');
                return redirect()->back()->withErrors(['email' => 'Tu cuenta está suspendida. Por favor, sigue el proceso de recuperación.']);
            }

            // Verificar la contraseña
            if (Hash::check(base64_encode($request->password), $user->password)) {
                Auth::login($user, $request->filled('remember'));
                $request->session()->regenerate();
                return redirect()->route('home');

            } else {//aqui se verifica sino hay un registro de intentos fallidos hoy
                
                $today = Carbon::today();
                $accessRequest = AccessRequest::where('email', $request->email)
                    ->whereDate('date', $today)
                    ->first();

                    if ($accessRequest) {// Si ya existe un registro de hoy, incrementar los intentos
                        $accessRequest->attempts += 1;
                        $accessRequest->save();

                        if ($accessRequest->attempts >= 3) {//aqui es donde suspendemos la cuenta, buscamos la cuenta y cambiamos el is_suspended = 1
                            
                            $user = User::where('email', $request->email);
                            $user->update(['is_suspended' => 1]);
                            
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
