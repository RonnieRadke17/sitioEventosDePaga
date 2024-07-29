<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User; // Importa tu modelo User
use Illuminate\Support\Facades\Auth; // Importa Auth para el login
use Illuminate\Support\Facades\Crypt;//se usa para la encriptacion
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

use App\Http\Controllers\EmailController;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    //codigo de verificacion debe ser de 6 numeros
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'paternal' => 'required|string|max:255',
            'maternal' => 'required|string|max:255',
            'birthdate' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(10)->format('Y-m-d'),
                'date_format:Y-m-d',
            ],
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',//poner validacion de minusculas y mayusculas y numeros
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $controladoremail = app(EmailController::class);
        $controladoremail->sendCodeViaEmail($request->email,"verification");

            //falta hacer la insersion del token en la DB
        $user = [
            'name' => $request->name,
            'paternal' => $request->paternal,
            'maternal'=> $request->maternal,
            'birthdate'=> $request->birthdate,
            'email'=> $request->email,
            //encriptar en base 64
            'password' =>base64_encode($request->password),
            //'password' => Crypt::encryptString($request->password), // Encriptar la contraseña
        ];    
        //dd($user);
        // Almacenar los datos del usuario en la sesión
        session(['user' => $user]);
        
        return redirect()->route('email-verification');
    }

    public function sendVerificationCode(){//aqui me falta mandar el mensaje de codigo renviado
        $user = session('user');
        $controladoremail = app(EmailController::class);
        $controladoremail->sendCodeViaEmail($user['email'],"verification");
        return redirect()->back()->with('message','Código reenviado');
    }


    public function emailVerification(Request $request)//aqui solamente mostramos la vista de la verificacion del email
    {
        // Obtener los datos del usuario desde la sesión
        $user = session('user');
    
        // Verificar si los datos del usuario están presentes en la sesión
        if (!$user) {
            return redirect()->route('register')->withErrors(['error' => 'Datos de usuario no encontrados.']);
        }
        // Renderizar la vista de verificación de correo con los datos del usuario
        return view('auth.email-verification', compact('user'));
    }

    public function checkEmailVerification(Request $request){//aqui verificamos el correo y registramos al usuario si esta bien el codigo
        $user = session('user');
        $emailController = app(EmailController::class);
        $response = $emailController->verifyCodeViaEmail($user['email'],$request->code,"verifyCodeRegister");
        
            // Manejar la respuesta
        if (strpos($response, 'Correo verificado con éxito') !== false) {
            //aqui registramos al usuario debido a que fue verificado correctamente
            
            $userRegistered = User::create([
                'name' => $user['name'],
                'paternal' => $user['paternal'],
                'maternal' => $user['maternal'],
                'birthdate' => $user['birthdate'],
                'email' => $user['email'],
                'password' => $user['password']
            ]);

            //aqui va loguear al usuario y redireccionar a el home
            Auth::login($userRegistered);
            return redirect()->route('home');
            
        } else {
            //poner todos los casos de porque esta mal es decir retornar con el error
            return redirect()->back()->withErrors($response);
        }
    }
}
