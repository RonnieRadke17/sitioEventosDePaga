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
use Carbon\Carbon;
use App\Http\Controllers\EmailController;
use App\Models\EmailVerification;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }else{
            return view('auth.register');
        }
        
    }

    //codigo de verificacion debe ser de 6 numeros
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3|max:20',
            'paternal' => 'required|string|max:255|min:4|max:20',
            'maternal' => 'required|string|max:255|min:4|max:20',
            'birthdate' => [
            'required',
            'date',
            'before_or_equal:' . now()->subYears(10)->format('Y-m-d'), // Mínimo 10 años
            'after_or_equal:' . now()->subYears(80)->format('Y-m-d'), // Máximo 80 años
            'date_format:Y-m-d',
            ],
            'gender' => 'required|in:M,F',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Llamar al controlador de correo para enviar el código de verificación
        $controladoremail = app(EmailController::class);
        $emailResponse = $controladoremail->sendCodeViaEmail($request->email, "verification");

            // Verificar si la respuesta es válida y manejar errores
        if (!$emailResponse || $emailResponse['status'] == false) {
            //session()->flash('error', $emailResponse['message']);
            return redirect()->back()->with('error', $emailResponse['message']);
            //session()->flash('error', $emailResponse['message'] ?? 'Error al enviar el código de verificación.');
            return redirect()->back();
        }

        //dd($emailResponse);
        // Continuar con el proceso de registro si todo salió bien
        $user = [
            'name' => $request->name,
            'paternal' => $request->paternal,
            'maternal' => $request->maternal,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => base64_encode($request->password),
        ];

        // Almacenar los datos del usuario en la sesión
        session(['user' => $user]);

        return redirect()->route('email-verification');
    }


    public function sendVerificationCode(){//aqui me falta mandar el mensaje de codigo renviado
        $user = session('user');
        $controladoremail = app(EmailController::class);
        $emailResponse = $controladoremail->sendCodeViaEmail($user['email'],"verification");
        //falta poner los mensajes de el envio
        return redirect()->back()->with(['message'=>$emailResponse['message']]);
    }


    public function emailVerification(Request $request)
{
    // Obtener los datos del usuario desde la sesión
    $user = session('user');
    
    if (!$user) {
        return redirect()->route('register')->withErrors(['error' => 'Datos de usuario no encontrados.']);
    }

        // Obtener la última verificación del correo electrónico
        $existingVerification = EmailVerification::where('email', $user['email'])
        ->orderBy('created_at', 'desc')
        ->first();

    // Convertir el campo 'expiration' a un objeto Carbon y asegurarse de que esté en la zona horaria correcta
    $expirationTime = Carbon::parse($existingVerification->expiration)->setTimezone('America/Mexico_City');
    $now = Carbon::now('America/Mexico_City');

    // Calcular el tiempo restante en segundos
    $remainingSeconds = $now->diffInSeconds($expirationTime);

    // Verificar si $remainingSeconds es negativo, y ajustarlo a 0 si lo es
    if ($remainingSeconds < 0) {
        $remainingSeconds = 0;
    }

    // Convertir los segundos restantes a minutos y segundos
    $remainingMinutes = floor($remainingSeconds / 60);
    $remainingSeconds = $remainingSeconds % 60;

    // Formatear el tiempo restante en "minutos:segundos"
    $remainingTimeFormatted = sprintf('%02d:%02d', $remainingMinutes, $remainingSeconds);

    // Renderizar la vista de verificación de correo con los datos del usuario y el tiempo restante
    return view('auth.email-verification', compact('user', 'remainingTimeFormatted'));
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
                'password' => $user['password'],
                'role_id' => 1
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
