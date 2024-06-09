<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Payment;

class PaypalController extends Controller
{

    //agregar que si el pago fue exitoso se haga la incersion en userEvent el id del usuario y del evento

    public function paypal(Request $request)
    {
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
                        //cambiar despues la moneda con la cual se cobra
                        "currency_code" => "USD",
                        "value" => $request->price
                    ]
                ]
            ]
        ]);
        //dd($response);
        if(isset($response['id']) && $response['id']!=null) {
            foreach($response['links'] as $link) {
                if($link['rel'] === 'approve') {
                    session()->put('product_name', $request->product_name);
                    session()->put('quantity', $request->quantity);
                    session()->put('event_id', $request->event);//send id event 
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
            $payment->user_id = auth()->id();;
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


            //aqui va la ruta de regreso a la pagina de eventos la cual tiene que mandar un mensaje de pago con exito de sweetalert
            return "Siuuuu";

            unset($_SESSION['product_name']);
            unset($_SESSION['quantity']);

        } else {
            //aqui va la ruta de regreso a la pagina de eventos la cual tiene que mandar un mensaje de pago con rechazado de sweetalert
            return redirect()->route('cancel');
        }
    }
    public function cancel()//aqui va la ruta de home con un mensaje 
    {
        //return "Pago cancelado unu.";
        return view('home');//aqui para regresar a home tienes que mandar todos los eventos otra vez
    }
}
