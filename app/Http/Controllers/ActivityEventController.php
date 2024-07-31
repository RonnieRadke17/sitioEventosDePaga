<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityEvent;
use App\Models\Activity;


class ActivityEventController extends Controller
{
    //aqui se hace el registro de las actividades seleccionadas y modificacion y eliminacion, sin formularios
   
    /**
     * Store a newly created resource in storage.
     */
    //$selectedActivities = $request->input('selected_activities');
    public function insertActivityEvent($selectedActivities)//inserta en la db las actividades del evento
    {
        
        try {
            // Iniciar una transacción para asegurar la integridad de los datos
            \DB::beginTransaction();

            // Obtener todas las actividades seleccionadas
            $selectedActivities = $request->input('selected_activities');

            foreach ($selectedActivities as $activityId) {
                // Obtener los géneros seleccionados para esta actividad
                $genders = $request->input("genders.$activityId", []);

                // Obtener las subactividades seleccionadas para esta actividad
                $subs = $request->input("subs.$activityId", []);

                // Iterar sobre los géneros seleccionados
                foreach ($genders as $gender => $value) {
                    // Iterar sobre las subactividades seleccionadas para este género
                    foreach ($subs[$gender] as $subId) {
                        // Crear un nuevo ActivityEvent
                        ActivityEvent::create([
                            'event_id' => $eventId,
                            'activity_id' => $activityId,
                            'gender' => $gender,
                            'sub_id' => $subId,
                        ]);
                    }
                }
            }

            // Commit de la transacción si todo está correcto
            \DB::commit();

            // Redireccionar o retornar una respuesta de éxito según tu flujo de aplicación
            return redirect()->route('event.index')->with('success', 'Actividades registradas correctamente.');
        } catch (\Exception $e) {
            // En caso de error, hacer rollback de la transacción y manejar el error
            \DB::rollback();
            // Imprimir la excepción para depuración
            dd($e);
            return back()->withInput()->withErrors(['error' => "Error"]);
        }
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}