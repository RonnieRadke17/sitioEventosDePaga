<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ActivityEvent;
use Carbon\Carbon;
//tabla intermedia de ActivityEvent

class UserEventController extends Controller
{
    public function index()//show all events
    {
         
        if (auth()->check()) {// El usuario está autenticado
            
            $subName = $this->getUserData('sub'); 
            // Filtrar los ActivityEvents donde el usuario puede participar
            $activityEventIds = ActivityEvent::where('gender',$this->getUserData('gender'))
                ->whereHas('sub', function($query) use ($subName) {
                    $query->where('name', 'LIKE', "%$subName%");
                })
                ->pluck('event_id'); // Obtener solo los IDs de los eventos
            
            // Obtener los eventos que:
            $events = Event::with(['images' => function($query) {
                    $query->where('type', 'cover'); // Solo obtener imágenes de tipo 'cover'
                }])
                ->where('registration_deadline', '>', now()) // Filtrar eventos cuya fecha límite de registro no haya pasado
                ->where(function($query) use ($activityEventIds) {
                    // Incluir eventos con actividades que el usuario puede participar
                    $query->whereIn('id', $activityEventIds)
                        // O incluir eventos que no tienen actividades (campo activities = 0)
                        ->orWhere('activities', 0);
                })
                ->get()
                ->map(function ($event) {
                    // Mostrar la imagen de tipo 'cover' si existe, si no, dejarlo en null
                    $event->first_image = $event->images->isNotEmpty() ? $event->images->first()->image : null;
                    return $event;
                });
            
            // Devolver la vista con los datos obtenidos
            return view('home', compact('events','activityEventIds'));
        
        } else {// El usuario no está autenticado mostrar todos los eventos//falta que si ya se paso la fecha no se muestre
            
            $events = Event::with(['images' => function($query) {
                $query->where('type', 'cover'); // Solo obtener imágenes de tipo 'cover'
            }])
            ->where('registration_deadline', '>', now()) // Filtrar eventos cuya fecha límite de registro no haya pasado
            ->get()
            ->map(function ($event) {
                // Mostrar la imagen de tipo 'cover' si existe, si no, dejarlo en null
                $event->first_image = $event->images->isNotEmpty() ? $event->images->first()->image : null;
                return $event;
            });
        
            return view('home', compact('events'));
        }
    }


    public function show($id)
    {
        //aqui verificamos que hay acts en este evento para el usuario que sino hay se redirecciona al home
        //en dado caso de que el evento no tenga actividades para nadie todos pueden entrar
        if (auth()->check()) {// El usuario está autenticado
            $subName = $this->getUserData('sub'); 
            // Desencriptar el ID del evento
            $decryptedId = decrypt($id);
            // Buscar el evento
            $event = Event::findOrFail($decryptedId);
            if($event->activities == 1){//si el evento tiene acts entonces se hace toda la validacion
                    // Filtrar las actividades del evento en las que el usuario puede participar
                $activityEventIds = ActivityEvent::where('event_id', $event->id)
                ->where('gender',$this->getUserData('gender')) // Filtrar por género
                ->whereHas('sub', function($query) use ($subName) {
                    // Filtrar por subName, que se relaciona con la edad
                    $query->where('name', 'LIKE', "%$subName%");
                })
                ->pluck('id'); // Obtener solo los IDs de las actividades válidas
                // Verificar si el usuario tiene permitido participar en alguna actividad

                if ($activityEventIds->isEmpty()) {//si no hay acts en las que pueda participar
                    // Si no hay actividades aptas para el usuario, redirigir a la página de inicio
                    return redirect()->route('home')->with('error', 'No tienes acceso a este evento.');

                }else{//else si si hay acts donde el pueda participar
                        // Desencriptar el ID del evento
                    $decryptedId = decrypt($id);
                    // Buscar el evento con sus actividades y sus relaciones en activity_events
                    $event = Event::findOrFail($decryptedId);
                    // Buscar el evento con sus relaciones (lugares en este caso)
                    $event1 = Event::with('places')->findOrFail($decryptedId);
                    // Obtener los lugares relacionados al evento
                    $places = $event1->places;
                    // Obtener todas las actividades del evento con sus géneros y subs correspondientes
                    $activities = ActivityEvent::where('event_id', $event->id)->with(['activity', 'sub'])->get();// Cargar la actividad y la sub
                    // Buscar el evento junto con sus imágenes
                    $eventIMG = Event::with('images')->findOrFail($decryptedId);

                    // Ordenar las imágenes según el valor del campo 'type'
                    $orderedImages = $eventIMG->images->sortBy(function ($image) {
                        switch ($image->type) {
                            case 'cover':
                                return 1;
                            case 'kit':
                                return 2;
                            case 'content':
                                return 3;
                            default:
                                return 4; // Si hubiera algún otro valor, lo ponemos al final
                        }
                    });

                    return view('user-event.show', compact('event', 'activities','places','orderedImages'));
                }

            }else{//se muestra el evento sin limitaciones porque no hay acts
                        // Desencriptar el ID del evento
                $decryptedId = decrypt($id);
                // Buscar el evento con sus actividades y sus relaciones en activity_events
                $event = Event::findOrFail($decryptedId);
                // Buscar el evento con sus relaciones (lugares en este caso)
                $event1 = Event::with('places')->findOrFail($decryptedId);
                // Obtener los lugares relacionados al evento
                $places = $event1->places;
                // Obtener todas las actividades del evento con sus géneros y subs correspondientes
                $activities = ActivityEvent::where('event_id', $event->id)->with(['activity', 'sub'])->get();// Cargar la actividad y la sub
                // Buscar el evento junto con sus imágenes
                $eventIMG = Event::with('images')->findOrFail($decryptedId);

                // Ordenar las imágenes según el valor del campo 'type'
                $orderedImages = $eventIMG->images->sortBy(function ($image) {
                    switch ($image->type) {
                        case 'cover':
                            return 1;
                        case 'kit':
                            return 2;
                        case 'content':
                            return 3;
                        default:
                            return 4; // Si hubiera algún otro valor, lo ponemos al final
                    }
                });

                return view('user-event.show', compact('event', 'activities','places','orderedImages'));
            }
             
        }else{//else para user no auth
                // Desencriptar el ID del evento
            $decryptedId = decrypt($id);
            // Buscar el evento con sus actividades y sus relaciones en activity_events
            $event = Event::findOrFail($decryptedId);
            // Buscar el evento con sus relaciones (lugares en este caso)
            $event1 = Event::with('places')->findOrFail($decryptedId);
            // Obtener los lugares relacionados al evento
            $places = $event1->places;
            // Obtener todas las actividades del evento con sus géneros y subs correspondientes
            $activities = ActivityEvent::where('event_id', $event->id)->with(['activity', 'sub'])->get();// Cargar la actividad y la sub
            // Buscar el evento junto con sus imágenes
            $eventIMG = Event::with('images')->findOrFail($decryptedId);

            // Ordenar las imágenes según el valor del campo 'type'
            $orderedImages = $eventIMG->images->sortBy(function ($image) {
                switch ($image->type) {
                    case 'cover':
                        return 1;
                    case 'kit':
                        return 2;
                    case 'content':
                        return 3;
                    default:
                        return 4; // Si hubiera algún otro valor, lo ponemos al final
                }
            });

            return view('user-event.show', compact('event', 'activities','places','orderedImages'));
        }
          
    }


    public function inscriptionFree(Request $request,$id)//obtenemos el id del evento y las acts mandadas por el user
    {
        /* 
            El usuario está autenticado
            verificar que exista un registro de una actividad con el genero y sub del usuario ejemplo 100mts M 20
            select en activityEvents donde se ponga que si hay un registro con idEvent,idActivity,Gender,idSub(likes) 
        */
        if (auth()->check()) {
            //buscar si el evento existe o no si hay capacidad o no
            $decryptedId = decrypt($id);
            // Buscar el evento
            $event = Event::findOrFail($decryptedId);
            //lo negamos aqui para tener todo el codigo abajo
            if(!$event){//si el evento NO existe continua
                return redirect()->back()->withErrors('error','valores incorrectos');      
            
            }else{
                if($event->activities == 1){//si el evento tiene acts entonces se hace toda la validacion
                    //buscamos las actividades del usuario son las del evento
                    // Obtener las actividades seleccionadas (si hay alguna)
                    $selectedActivities = $request->input('activities', []);
                    if($selectedActivities == null){
                        return redirect()->back()->withErrors('error','Necesitas seleccionar una actividad minimo'); 
                    }
                    if($selectedActivities > 4){
                        return redirect()->back()->withErrors('error','Solo puedes seleccionar 3 como minimo'); 
                    }

                    //buscar si las acts del user estan el la db pero que sean las correctas
                    //hacer comparacion de id_event,id_act,gender,sub usar likes
                    // Desencriptar el id del evento
                $decryptedId = decrypt($id);
                
                // Obtener el género del usuario
                $userGender = $this->getUserData('gender');

                // Obtener el sub del usuario (como parte del nombre)
                $subName = $this->getUserData('sub');

                // Consulta para validar que todas las actividades seleccionadas existen y cumplen con los criterios
                $validActivities = ActivityEvent::where('event_id', $decryptedId)
                    ->whereIn('activity_id', $selectedActivities) // Verificar que las actividades seleccionadas existan
                    ->where('gender', $userGender) // Verificar que coincidan con el género del usuario
                    ->whereHas('sub', function ($query) use ($subName) {
                        $query->where('name', 'LIKE', "%$subName%"); // Verificar que el sub contenga el valor de subName
                    })
                    ->count(); // Contar cuántas actividades cumplen con los criterios

                // Verificar si la cantidad de actividades válidas coincide con la cantidad de actividades seleccionadas
                if ($validActivities != count($selectedActivities)) {
                    // Redireccionar con mensaje de error si alguna actividad no existe o no cumple los criterios
                    return redirect()->back()->withErrors('Una o más actividades seleccionadas no son válidas para este usuario.');
                }
                return 'Actividades validadas con éxito.';
                //si todo fue exitoso usar metodo atach para hacer el registro del usuario en el evento
                //despues insertar las acts en la tabla de userEventActs que tiene relacion de uno a muchos

                

                }else{
                    //si no tiene actividades entonces solo se valida que no este inscrito y se inscribe
                    return redirect()->back()->with('message','hola');  
                }
            
                   
            }
        }else{//redireccionamos para que se registre el user
            
            return view('auth/login');
        }
    }

    //aqui metodo de inscripcion de paga

    //metodo get de sub,gender del User
    public function getUserData($valueOP){
        $user = auth()->user();
        switch($valueOP){
            case $valueOP == 'gender':
                 
                $gender = $user->gender; // Género
                return $gender;
                break;

            case $valueOP == 'sub':

                $birthdate = $user->birthdate; // Suponiendo que 'birthdate' es una fecha válida en formato 'YYYY-MM-DD'
                $currentYear = Carbon::now()->year; // Obtén el año actual
                // Obtener el año de nacimiento
                $birthYear = Carbon::parse($birthdate)->year;
                
                // Calcular la edad que el usuario va a tener o tiene en el año vigente
                $ageThisYear = $currentYear - $birthYear;            
                
                // Parte del nombre del sub que quieres comparar
                $subName = $ageThisYear; 

                return $subName;
        }
    
    }


}
