<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\http\Requests\AuthRequest\RegisterRequest;
use App\Services\EmailService\EmailService;
use App\Models\UserToken;
use Carbon\Carbon;



use Illuminate\Support\Facades\Hash;

use App\Models\User; // Importa tu modelo User
use Illuminate\Support\Facades\Auth; // Importa Auth para el login
use Illuminate\Support\Facades\Crypt; // Se usa para la encriptación
use Illuminate\Support\Facades\Mail;

/* use App\Http\Controllers\EmailController;
use App\Models\EmailVerification; */


class RegisterController extends Controller
{
    protected $email;
    public function __construct(EmailService $email)
    {
        $this->email = $email;
    }

    public function form()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        } else {
            return view('auth.register');
        }
    }

    // Código de verificación de 6 dígitos
    public function processRegister(RegisterRequest $request)
    {
        /* manda a llamar el service de email para mandar el correo de verificación */
        $sendVerificationCode = $this->email->sendCode($request->email,"email_verification");

        // Verificar si la respuesta es válida y manejar errores
        if (!$sendVerificationCode || $sendVerificationCode['status'] == false) {
            return redirect()->back()->withErrors($sendVerificationCode['message']);
        }

        $user = $request->validated();
        // Almacenar los datos del usuario en la sesión
        session(['user' => $user]);

        return redirect()->route('email-verification');
    }

    public function sendVerificationCode()
    {
        $user = session('user');
        $controladoremail = app(EmailController::class);
        $emailResponse = $controladoremail->sendCodeViaEmail($user['email'], "verification");

        return redirect()->back()->with(['message' => $emailResponse['message']]);
    }

    public function emailVerification(Request $request)
    {
        $user = session('user');
        
        if (!$user) {
            return redirect()->route('register')->withErrors(['error' => 'Datos del usuario no encontrados.']);
        }

        $existingVerification = UserToken::where('email', $user['email'])
            ->where('type', "email_verification") // parámetro adicional
            ->orderBy('created_at', 'desc')
            ->first();

        $expirationTime = Carbon::parse($existingVerification->expiration)->setTimezone('UTC');
        $now = Carbon::now('UTC');

        // Calcular el tiempo restante en segundos y formatearlo
        $remainingSeconds = max($now->diffInSeconds($expirationTime), 0);
        $remainingMinutes = floor($remainingSeconds / 60);
        $remainingTimeFormatted = sprintf('%02d:%02d', $remainingMinutes, $remainingSeconds % 60);

        return view('auth.email-verification', compact('user', 'remainingTimeFormatted', 'remainingSeconds'));
    }

    public function checkEmailVerification(Request $request)//falta este método
    {
        $user = session('user');
        $emailController = app(EmailController::class);
        $response = $emailController->verifyCodeViaEmail($user['email'], $request->code, "verifyCodeRegister");

        // Manejar la respuesta
        if (strpos($response, 'Correo verificado con éxito') !== false) {
            // Registrar al usuario ya que fue verificado correctamente
            $userRegistered = User::create([
                'name' => $user['name'],
                'paternal' => $user['paternal'],
                'maternal' => $user['maternal'],
                'birthdate' => $user['birthdate'],
                'email' => $user['email'],
                'password' => $user['password'],
                'role_id' => 1
            ]);

            // Iniciar sesión y redireccionar al home
            Auth::login($userRegistered);
            return redirect()->route('home');
        } else {
            return redirect()->back()->withErrors($response);
        }
    }
}
