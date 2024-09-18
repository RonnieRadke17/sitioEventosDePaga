<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;


class MercadoPagoController extends Controller
{
    public function index(){

        $preferenceId = $this->generatePreferenceId();
        return view('mp.mp',compact('preferenceId'));
    }



    public function generatePreferenceId(){

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
                
        $client = new PreferenceClient();
        $preference = $client->create([
        "items"=> array(
            array(
            "title" => "Mi producto",
            "quantity" => 1,
            "unit_price" => 85
            )
        )
        ]);
        // austria dijo que mandar un array de los ids de las actividades que se registra y de todas maneras mostrarlas
        //consiliaciopn cuando genero la orden de pago catalog de ordenes de pago
        //falta redireccionar a la pagina principal o una pagina de evento pagado con exito
        //y hacer la incersion de la informacion a la base de datos del pago y de lo que se pago
        //como hacer la incersion en la base si guardando por mandar valores o en localstorage
        return $preference->id;
    }


}
