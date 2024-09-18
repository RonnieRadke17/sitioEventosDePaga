<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Payment;
use Illuminate\Support\Facades\Crypt;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\ActivityEventUser;

class PaypalController extends Controller
{
    public function paypal(Request $request)
    {
        //obtencion de datos del evento
        try {
            // Obtén y desencripta el valor enviado por el formulario
            $decryptedId = Crypt::decrypt($request->input('event'));
            $activities = $request->input('activities');
        } catch (\Exception $e) {
            // Maneja errores de desencriptación si los hay
            return redirect()->back()-withErrors();
        }

        $event = Event::find($decryptedId);
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('success'),
                "cancel_url" => route('cancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "MXN",
                        "value" => $event->price
                        //"value" => $request->price
                    ]
                ]
            ]
        ]);
    
        if(isset($response['id']) && $response['id']!=null) {
            foreach($response['links'] as $link) {
                if($link['rel'] === 'approve') {
                    session()->put('product_name', $event->name);
                    session()->put('quantity',"1");
                    session()->put('event_id', $decryptedId);//send id event 
                    session()->put('activities',$request->session()->get('activities'));//id de las actividades
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('cancel');
        }
    }





    public function success(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);
        //dd($response);
        if(isset($response['status']) && $response['status'] == 'COMPLETED') {
            
            // Insert data into database
            $payment = new Payment;
            $payment->payment_id = $response['id'];
            //id of event and user
            $payment->user_id = auth()->id();
            $payment->event_id = session()->get('event_id');
            $payment->product_name = session()->get('product_name');
            $payment->quantity = session()->get('quantity');
            $payment->amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $payment->currency = $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'];
            $payment->payer_name = $response['payer']['name']['given_name'];
            $payment->payer_email = $response['payer']['email_address'];
            $payment->payment_status = $response['status'];
            $payment->payment_method = "PayPal";
            $payment->save();

            //insert into userEvent donde va el idUser y idEvent como a su vez los id de las actividades
            $eventUser = EventUser::create([
                'user_id' => auth()->id(),
                'event_id' => session()->get('event_id'),
            ]);
            
            $event = Event::find(session()->get('event_id'));

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

            //falta poner el payment request como success
            
            //aqui va la ruta de regreso a la pagina de eventos la cual tiene que mandar un mensaje de pago con exito de sweetalert
            return redirect()->route('home')->with('success', 'Pago aprovado');
            unset($_SESSION['product_name']);
            unset($_SESSION['quantity']);

        } else {
            //aqui va la ruta de regreso a la pagina de eventos la cual tiene que mandar un mensaje de pago con rechazado de sweetalert
            return redirect()->route('home')->withErrors(['error' => 'error al procesar el pago']);
        }
    }
    public function cancel()//aqui va la ruta de home con un mensaje 
    {
        //return "Pago cancelado unu.";
        return redirect()->route('home')->withErrors(['error' => 'error al procesar el pago']);
        
    }


    
}
