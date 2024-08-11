<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;//se usa para la encriptacion

class LoginController extends Controller
{
    public function login(Request $request){

        /*
            agregar validator y encriptar la contrasena a mandar por medio de Encript

            como a su vez validar que no se puedan hacer mas de 3 intentos de acceso a la cuenta

            poner que si se fallan 3 intentos suspender la cuenta y poner la opcion de mandar un codigo de activacion de cuenta 
        */
        $credentials = [
            'email' => $request->email,
            'password' => base64_encode($request->password),//encriptar en base 64
        ];
        
        $remember = ($request['remember'] ?? false);

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',//poner validacion de minusculas y mayusculas y numeros
        ]);


        if(Auth::attempt($credentials, $remember)){
            $request->session()->regenerate();
            
            return redirect()->route('home');
        }else{

            //dd($credentials);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            //$this->error($credentials);
            //return redirect('login')->back()->withErrors();
            //return redirect()->back()->withErrors('error','');
//agregar validaciones de si no existe el correo en la DB 
        //validacion de la contrasena que no sea correcta
        //si los dos datos son incorrectos

        }

        

    }
}
 