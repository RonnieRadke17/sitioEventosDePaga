<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User; // Importa tu modelo User
use Illuminate\Support\Facades\Auth; // Importa Auth para el login
use Illuminate\Support\Facades\Crypt; // Se usa para la encriptación
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Http\Controllers\EmailController;
use App\Models\EmailVerification;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        } else {
            return view('auth.register');
        }
    }

    // Código de verificación de 6 dígitos
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20|min:3',
            'paternal' => 'required|string|max:20|min:4',
            'maternal' => 'required|string|max:20|min:4',
            'birthdate' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(10)->format('Y-m-d'), // Mínimo 10 años
                'after_or_equal:' . now()->subYears(80)->format('Y-m-d'),  // Máximo 80 años
                'date_format:Y-m-d',
            ],
            'gender' => 'required|in:M,F',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',/* |regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/ */
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Llamada al controlador de correo para enviar el código de verificación
        $controladoremail = app(EmailController::class);
        $emailResponse = $controladoremail->sendCodeViaEmail($request->email, "verification");

        // Verificar si la respuesta es válida y manejar errores
        if (!$emailResponse || $emailResponse['status'] == false) {
            return redirect()->back()->with('error', $emailResponse['message']);
        }

        // Almacenar los datos del usuario en la sesión
        $user = [
            'name' => $request->name,
            'paternal' => $request->paternal,
            'maternal' => $request->maternal,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => base64_encode($request->password),
        ];
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
            return redirect()->route('register')->withErrors(['error' => 'Datos de usuario no encontrados.']);
        }

        $existingVerification = EmailVerification::where('email', $user['email'])
            ->orderBy('created_at', 'desc')
            ->first();

        $expirationTime = Carbon::parse($existingVerification->expiration)->setTimezone('America/Mexico_City');
        $now = Carbon::now('America/Mexico_City');

        // Calcular el tiempo restante en segundos y formatearlo
        $remainingSeconds = max($now->diffInSeconds($expirationTime), 0);
        $remainingMinutes = floor($remainingSeconds / 60);
        $remainingTimeFormatted = sprintf('%02d:%02d', $remainingMinutes, $remainingSeconds % 60);

        return view('auth.email-verification', compact('user', 'remainingTimeFormatted', 'remainingSeconds'));
    }

    public function checkEmailVerification(Request $request)
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
