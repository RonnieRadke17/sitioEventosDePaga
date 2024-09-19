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

class StripeController extends Controller
{
    public function processPayment(Request $request)
    {
        try {
            // Configura la clave secreta de Stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // Crear el cargo
            //nota multiplicar el monto del producto por 100 porque se tiene que mandar en centavos :)
            $charge = Charge::create([
                'amount' => 1000, // El monto debe ser en centavos (10 USD = 1000 centavos)
                'currency' => 'mxn',
                'source' => $request->stripeToken,
                'description' => 'Pago único con Stripe',
            ]);

            //incercion en payments,EventUser,ActivitYEventUser
             $payment = Payment::create([
                'payment_id' => $charge->id,
                'user_id' => 1,//cambiar
                'event_id' => session()->get('id'),//cambiar
                'product_name' => 1,//cambiar
                'quantity' => 1,
                'amount' => 'costo por 1000',
                'currency' => 'mxn',
                'payer_name' => 'hola',
                'payer_email' => 'hola',
                'payment_status' => 'COMPLETED',
                'payment_method' => 'Stripe',
                
            ]);

            
             //insert into userEvent donde va el idUser y idEvent como a su vez los id de las actividades
             $eventUser = EventUser::create([
                'user_id' => auth()->id(),
                'event_id' => session()->get('id'),
            ]);
            
            $event = Event::find(session()->get('id'));

            if($event->activities == 1){
                // Recupera los datos de la sesión
                $activities = $request->session()->get('activities');
                    // Recorrer las actividades seleccionadas y desencriptarlas
                foreach ($activities as $encryptedActivityId => $genders) {
                    foreach ($genders as $encryptedGender => $subIds) {
                        foreach ($subIds as $encryptedSubId => $value) {
                            if ($value == 'on') { // Verificar si el checkbox fue marcado
                                try {
                                    // Desencriptar los valores
                                    $activityId = Crypt::decrypt($encryptedActivityId);
                                    $gender = Crypt::decrypt($encryptedGender);
                                    $subId = Crypt::decrypt($encryptedSubId);

                                    // Insertar en la tabla 'activity_event_users'
                                    ActivityEventUser::create([
                                        'event_user_id' => $eventUser->id,
                                        'activity_id' => $activityId,
                                        'gender' => $gender, // Inserta el género
                                        'sub_id' => $subId,  // Inserta el sub_id
                                    ]);

                                } catch (DecryptException $e) {
                                    // Error en la desencriptación
                                    return redirect()->back()->withErrors(['error' => 'Uno o más valores seleccionados son inválidos.']);
                                }
                            }
                        }
                    }
                }
            } 
            //elimina los valores extraidos
            session()->forget('activities');
            session()->forget('id');

            return redirect()->route('home')->with('success','Pago realizado correctamente');
        } catch (Exception $e) {
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }
}
