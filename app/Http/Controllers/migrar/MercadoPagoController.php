<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

class MercadoPagoController extends Controller
{
    // Método para mostrar la página de pago y generar la preferencia
    public function index()
    {
        // Generar la preferencia
        $preferenceId = $this->generatePreferenceId();

        // Verificar si se generó la preferencia correctamente
        if (is_string($preferenceId)) {
            // Pasar el ID de la preferencia a la vista
            return view('mp.mp', compact('preferenceId'));
        } else {
            // Manejar el error en la vista si no se pudo generar la preferencia
            return view('mp.error', ['error' => 'No se pudo generar la preferencia de pago.']);
        }
    }

    // Método para generar el ID de la preferencia de MercadoPago
    public function generatePreferenceId()
    {
        try {
            // Configurar el token de acceso de MercadoPago
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

            // Crear un nuevo cliente de preferencia
            $client = new PreferenceClient();

            // Crear la preferencia
            $preference = $client->create([
                "items" => [
                    [
                        "title" => "Mi producto",
                        "quantity" => 1,
                        "unit_price" => 85
                    ]
                ]
            ]);

            // Verificar si la preferencia tiene un ID
            if (isset($preference->id)) {
                return $preference->id;
            } else {
                \Log::error('No se pudo generar la preferencia de MercadoPago. Respuesta: ' . json_encode($preference));
                return null;
            }

        } catch (\Exception $e) {
            // Capturar cualquier excepción y registrarla
            \Log::error('Error al generar la preferencia de MercadoPago: ' . $e->getMessage());
            return null;
        }
    }

    // Método para recibir las notificaciones del webhook de MercadoPago
    public function handleWebhook(Request $request)
    {
        // Obtener los datos enviados por MercadoPago
        $data = $request->all();

        // Verificar si la notificación es del tipo 'payment'
        if (isset($data['type']) && $data['type'] === 'payment') {
            // Obtener el ID del pago
            $paymentId = $data['data']['id'];

            // Configurar el token de MercadoPago
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

            // Obtener los detalles del pago desde MercadoPago
            $payment = Payment::find_by_id($paymentId);

            // Verificar el estado del pago y realizar acciones específicas
            if ($payment->status === 'approved') {
                // Pago aprobado
                \Log::info('Pago aprobado con ID: ' . $payment->id);
                return redirect()->route('home');
            } elseif ($payment->status === 'pending') {
                // Pago pendiente
                \Log::info('Pago pendiente con ID: ' . $payment->id);
            } elseif ($payment->status === 'rejected') {
                // Pago rechazado
                \Log::info('Pago rechazado con ID: ' . $payment->id);
            }

            // Responder con éxito a MercadoPago
            return response()->json(['status' => 'success'], 200);
        }

        // Responder con error si los datos no son válidos
        return response()->json(['error' => 'Datos no válidos'], 400);
    }
}
