<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ActivityEvent;
use App\Models\EventUser;
use App\Models\ActivityEventUser;
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
            //aqui si el usuario ya esta registrado lo redireccionamos a el home
            
            if($this->validateEventUser($id)){//validar que el usuario no este inscrito
                return redirect()->route('home')->withErrors(['error' => 'Ya estas registrado en ese evento.']);
            }

            if($this->validateCapacity($id) == 'withoutcapacity'){//No hay capacidad en el evento
                return redirect()->route('home')->withErrors(['error' => 'Evento lleno']);
            }

            if($this->validateRegistrationDeadLine($id)){//la fecha del evento ya ha pasado
                return redirect()->route('home')->withErrors(['error' => 'Evento expirado']);
            }

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
        
        if (auth()->check()) {//usuario autentificado
            $decryptedId = decrypt($id);
            
            $event = Event::findOrFail($decryptedId);// Buscar el evento
            // Obtener las actividades seleccionadas (si hay alguna)
            $selectedActivities = $request->input('activities', []);
            //lo negamos aqui para tener todo el codigo abajo
            if(!$event){//si el evento NO existe continua     
                return redirect()->back()->withErrors(['error' => 'valores incorrectos']);
            }

            if($this->validateEventUser($id)){//validar que el usuario no este inscrito
                return redirect()->route('home')->withErrors(['error' => 'Ya estas registrado en ese evento.']);
            }

            if($this->validateRegistrationDeadLine($id)){//la fecha del evento ya ha pasado
                return redirect()->route('home')->withErrors(['error' => 'Evento expirado']);
            }

            //validar si el evento es con capacidad o sin validamos si el evento tiene o no capacidad
            if($this->validateCapacity($id) == 'withoutlimit'){//el evento no tiene capacidad limite
                //metodo que inscribe 
                $message = $this->inscription($id,$selectedActivities);
                //retornar a una vista
                return redirect()->route('home')->with('success', $message);

            }else if($this->validateCapacity($id) == 'withcapacity'){//hay capacidad en el evento
                //aqui se inscribe al evento metodo que haga eso aqui
                $message = $this->inscription($id,$selectedActivities);
                //retornar a una vista
                return redirect()->route('home')->with('success', $message);

            }else if($this->validateCapacity($id) == 'withoutcapacity'){//No hay capacidad en el evento

                return redirect()->route('home')->withErrors(['error' => 'Evento agotado']);
            }
                //si el codigo falla aqui va desde la linea if($event->activities == 1){

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

    //metodo para validar que un usuario no esta inscrito a un evento
    public function validateEventUser($id){
        $decryptedId = decrypt($id);
        $user = auth()->user();
        $eventUser = EventUser::where('user_id', $user->id)->where('event_id',$decryptedId)->first();
        if($eventUser){
            return true;//encontró un registro del usuario
        }else{
            return false;//NO encontró un registro del usuario
        }
    }

    //metodo para validar la capacidad del evento
    public function validateCapacity($id){
        $decryptedId = decrypt($id);
        $event = Event::find($decryptedId);
        $limitedCapacity = $event->is_limited_capacity;

        if($limitedCapacity){//si es de capacidad limitada
            $capacity = $event->capacity;
            $users = EventUser::where('event_id', $decryptedId)->count();
            if($users < $capacity){
                return 'withcapacity';//hay capacidad
            }else{
                return 'withoutcapacity';//NO hay capacidad
            }    
        }else{
            return 'withoutlimit';
        }
        
    }    
    
    public function validateRegistrationDeadLine($id){
        $decryptedId = decrypt($id);
        $event = Event::find($decryptedId);
        $registrationDeadline = $event->registration_deadline;
            // Comparar la fecha límite de registro con la fecha actual
        if (Carbon::parse($registrationDeadline)->isPast()) {
            // Si la fecha límite ha pasado, devolver un mensaje de error
            return true;//la fecha ya ha pasado
        }
    }
    
    //metodo el cual inscribe al usuario en el evento aqui se inscribe si es con acts o sin acts
    public function inscription($id,$selectedActivities){//id del evento,y actividades

        $decryptedId = decrypt($id);
            
        // Buscar el evento
        $event = Event::findOrFail($decryptedId);

        if($event->activities == 1){//si el evento tiene acts entonces se hace toda la validacion
            if($selectedActivities == null){
                return redirect()->back()->withErrors(['error' => 'Necesitas seleccionar una actividad mínimo']);
            }
            if (count($selectedActivities) > 3) {
                return redirect()->back()->withErrors(['error' => 'Solo puedes seleccionar 3 actividades como máximo']);
            }
            /* 
                buscar si las acts del user estan el la db pero que sean las correctas
                hacer comparacion de id_event,id_act,gender,sub usar likes
                Desencriptar el id del evento
                Obtener el género del usuario 
            */
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
                return redirect()->back()->withErrors(['error' => 'Una o más actividades seleccionadas no son válidas para este usuario.']);
            }
            //incersion en la tabla intermedia
            $user = auth()->user();
            $eventUser = EventUser::create([
                'user_id' => $user->id,
                'event_id' => $decryptedId,
            ]);
            
            //incersion de las act en la tabla de ActivityEventUser
            // Bucle para insertar cada actividad en 'activity_event_users'
            foreach ($selectedActivities as $activityId) {
                // Crear el registro en la tabla 'activity_event_users'
                ActivityEventUser::create([
                    'event_user_id' => $eventUser->id,  // El ID del registro en 'event_user'
                    'activity_id'   => $activityId,     // El ID de la actividad
                ]);
            }
            //return redirect()->route('home')->with('success', 'Registro exitoso');
            return 'Registro realizado exitosamente.';
        }else{
            //el evento no tiene actividades entonces solo se inscribe
            //incersion en la tabla intermedia
            $user = auth()->user();
            $eventUser = EventUser::create([
                'user_id' => $user->id,
                'event_id' => $decryptedId,
            ]);
            return 'Registro realizado exitosamente.';
            //return redirect()->route('home')->with('success', 'Registro exitoso');
        }
    }


}
