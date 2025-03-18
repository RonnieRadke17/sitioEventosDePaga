<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ActivityEvent;
use App\Models\Activity;
use App\Models\Event;
use App\Models\Sub;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ActivityEventController extends Controller
{/* gestion de actividades de eventos */

    
    public function create(string $id){//get id for event
        $value = $this->validateRegistrationDeadline($id);//si el valor es tal entonces si se puede
        if($value){
            $event = Event::find(decrypt($id));
            $activities = Activity::all();
            $subs = Sub::all();
            return view('activity-event.create',compact('id','event', 'activities', 'subs'));
        }else{
            return redirect()->route('home')->withErrors(['error' => 'No se puede agregar actividades porque la fecha de inscripción ya pasó.']);
        }    
    }
    
    public function store(Request $request)
    {
        //dd($request);
        $id = $request->id_event;
        $encryptedId = $request->id_event;
        $value = $this->validateRegistrationDeadline($id);//validacion de fecha de inscripcion si todavia es valido el insertar acts
    
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'is_with_activities' => 'required|in:0,1',
            'selected_activities' => 'array|min:1',
            'selected_activities.*' => 'integer|exists:activities,id',
            'genders' => 'array',
            'genders.*' => 'array',
            'genders.*.*' => 'array',
            'genders.*.*.*' => 'integer|exists:subs,id',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        if (!$value) {
            return redirect()->route('home')->withErrors(['error' => 'No se puede agregar actividades porque la fecha de inscripción ya pasó.']);
        }
    
        if ($request->is_with_activities != "1") {//si no hay actividades solo redireccionas a el evento especifico
            return redirect()->route('event.show', $encryptedId);//redireccionar a ruta de show
        }
    
        /* try {
            $result = DB::transaction(function () use ($request) {
                $decryptedId = Crypt::decrypt($request->id_event);
    
                foreach ($request->selected_activities as $activityId) {
                    if (!isset($request->genders[$activityId]) || empty($request->genders[$activityId])) {
                        continue;
                    }
                    //falta encriptar los ids de las actividades en las vistas y descencriptar en el back
                    //como a su vez validar que la actividad tenga el genero mixto en dado caso de que llegue un registro con mix
                    foreach ($request->genders[$activityId] as $gender => $subIds) {
                        foreach ($subIds as $subId) {
                            ActivityEvent::create([
                                'event_id'   => $decryptedId,
                                'activity_id' => $activityId,
                                'gender'     => $gender,
                                'sub_id'     => $subId,
                            ]);
                        }
                    }
                }
    
                Event::where('id', $decryptedId)->update(['activities' => '1']);
            });

            if($result){
                return redirect()->route('home')->with('success', 'Actividades registradas correctamente.');
            }else {
                return redirect()->route('home')->with('success', 'Error.');
            }
    
            
        } catch (\Exception $e) {
            return redirect()->route('home')->withErrors(['error' => 'Error al registrar actividades: ' . $e->getMessage()]);
        } */
    }
       

    public function edit(string $id){
      
    }

    public function update(Request $request, $id)
    {
        
        
    }




    //metodo de validacion para store y update
    public function validateRegistrationDeadline(String $id){
        try {
            //revisamos si el id del evento es válido
            try {
                $decryptedId = Crypt::decrypt($id);

            }catch (DecryptException $e) {// Error en la desencriptación
                
                return redirect()->route('home')->withErrors(['error' => 'Error al acceder.']);
            }

            //buscamos el evento por el id,pero primero lo tenemos  que descencriptar
            $event = Event::find($decryptedId);
            if($event){//si encontro el evento entonces continua
                // Obtenemos la fecha de inscripción
                $registrationDeadline = Carbon::parse($event->registration_deadline); // Convertimos a un objeto Carbon
                $now = Carbon::now(); // Fecha y hora actual
                // Comparación
                if ($registrationDeadline->greaterThanOrEqualTo($now)) { //si la fecha de inscripcion no ha pasado puede registrar acts
                    //metodo para registrar acts
                    return true;
                } else {
                    // Si la fecha ya pasó, mostramos error
                    return false;
                }
                
            }else{
                return redirect()->route('home')->withErrors(['error' => 'El evento no existe.']);
            }
            
        } catch (Exception $e) {
            return redirect()->route('home')->withErrors(['error' => 'Ocurrió un error.']);
        }
    }
}
