<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Exception;
use App\Models\Payment;
use Illuminate\Support\Facades\Crypt;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\ActivityEventUser;
use Illuminate\Support\Facades\Validator;

class StripeController extends Controller
{
    public function processPayment(Request $request)
{
    // Validaciones antes de procesar el pago
    $validator = Validator::make($request->all(), [
        'name' => [
            'required',
            'string',
            'min:2', // Mínimo de 2 caracteres para el nombre
            'max:255', // Máximo de 255 caracteres para el nombre
            'regex:/^[a-zA-Z]+$/u' // Solo letras, sin espacios ni guiones
        ],
        'email' => 'required|email|max:55', // Correo electrónico válido
        'stripeToken' => 'required', // Token de Stripe es requerido
    ], [
        // Mensajes de error personalizados
        'name.required' => 'El nombre es obligatorio.',
        'name.string' => 'El nombre debe ser una cadena de texto.',
        'name.min' => 'El nombre debe tener al menos 2 caracteres.',
        'name.max' => 'El nombre no debe exceder los 55 caracteres.',
        'name.regex' => 'El nombre solo puede contener letras, espacios y guiones.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico no es válido.',
        'email.max' => 'El correo electrónico no debe exceder los 255 caracteres.',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Validar que el usuario esté autenticado
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para realizar el pago.');
    }
    

    try {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $event = Event::find(session()->get('id'));

        if (!$event) {
            return back()->with('error', 'Evento no encontrado.');
        }

        // Verificar si el usuario ya está registrado en el evento
        $existingEventUser = EventUser::where('user_id', auth()->id())
                                       ->where('event_id', session()->get('id'))
                                       ->first();

        if ($existingEventUser) {
            return back()->with('error', 'Ya estás registrado en este evento.');
        }

        // Verificar si el usuario ya ha realizado un pago para este evento
        $existingPayment = Payment::where('user_id', auth()->id())
                                  ->where('event_id', session()->get('id'))
                                  ->first();

        if ($existingPayment) {
            return back()->with('error', 'Ya has realizado un pago para este evento.');
        }

        // Crear el cargo
        $charge = Charge::create([
            'amount' => $event->price * 100,
            'currency' => 'mxn',
            'source' => $request->stripeToken,
            'description' => 'Pago único con Stripe',
        ]);

        // Inserción en payments
        $payment = Payment::create([
            'payment_id' => $charge->id,
            'user_id' => auth()->id(),
            'event_id' => session()->get('id'),
            'product_name' => $event->name,
            'quantity' => 1,
            'amount' => $event->price,
            'currency' => 'mxn',
            'payer_name' => $request->name,
            'payer_email' => $request->email,
            'payment_status' => 'COMPLETED',
            'payment_method' => 'Stripe',
        ]);

        // Inserción en EventUser
        $eventUser = EventUser::create([
            'user_id' => auth()->id(),
            'event_id' => session()->get('id'),
        ]);

        // Manejo de actividades, si existen
        if ($event->activities == 1) {
            $activities = $request->session()->get('activities');
            foreach ($activities as $encryptedActivityId => $genders) {
                foreach ($genders as $encryptedGender => $subIds) {
                    foreach ($subIds as $encryptedSubId => $value) {
                        if ($value == 'on') {
                            try {
                                $activityId = Crypt::decrypt($encryptedActivityId);
                                $gender = Crypt::decrypt($encryptedGender);
                                $subId = Crypt::decrypt($encryptedSubId);

                                ActivityEventUser::create([
                                    'event_user_id' => $eventUser->id,
                                    'activity_id' => $activityId,
                                    'gender' => $gender,
                                    'sub_id' => $subId,
                                ]);
                            } catch (DecryptException $e) {
                                return redirect()->back()->withErrors(['error' => 'Uno o más valores seleccionados son inválidos.']);
                            }
                        }
                    }
                }
            }
        }

        // Eliminar los valores de sesión
        session()->forget('activities');
        session()->forget('id');

        return redirect()->route('home')->with('success', 'Pago realizado correctamente');
    } catch (Exception $e) {
        return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
    }
}

}
