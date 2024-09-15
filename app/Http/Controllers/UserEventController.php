<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ActivityEvent;
use App\Models\EventUser;
use App\Models\ActivityEventUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class UserEventController extends Controller
{
    public function index()//show all events
    {
         
        if (auth()->check()) {
            // Obtener datos del usuario autenticado
            $subName = $this->getUserData('sub'); 
            $userId = auth()->id(); // Obtener el ID del usuario autenticado
        
            // Filtrar los ActivityEvents donde el usuario puede participar
            $activityEventIds = ActivityEvent::where('gender', $this->getUserData('gender'))
            ->orWhere('gender', 'Mix')//mostrar tambien las que sean mixtas
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
                ->where(function($query) {
                    $query->where('is_limited_capacity', 0) // Permitir eventos sin límite de capacidad
                          ->orWhere(function ($query) {
                              $query->where('is_limited_capacity', 1) // Si tiene capacidad limitada
                                    ->whereHas('eventUser', function ($query) {
                                        // Contar usuarios registrados y comparar con la capacidad del evento
                                        $query->havingRaw('COUNT(*) < events.capacity');
                                    });
                          });
                })
                ->whereDoesntHave('eventUser', function($query) use ($userId) {
                    // Excluir los eventos donde el usuario ya esté inscrito
                    $query->where('user_id', $userId);
                })
                ->get()
                ->map(function ($event) {
                    // Mostrar la imagen de tipo 'cover' si existe, si no, dejarlo en null
                    $event->first_image = $event->images->isNotEmpty() ? $event->images->first()->image : null;
                    return $event;
                });
            
            // Devolver la vista con los datos obtenidos
            return view('user-event/home', compact('events', 'activityEventIds'));
        }
        else {// El usuario no está autenticado mostrar todos los eventos//falta que si ya se paso la fecha no se muestre
            
            $events = Event::with(['images' => function($query) { 
                $query->where('type', 'cover'); // Solo obtener imágenes de tipo 'cover'
            }])
            ->where('registration_deadline', '>', now()) // Filtrar eventos cuya fecha límite de registro no haya pasado
            ->where(function ($query) {
                $query->where('is_limited_capacity', 0) // Permitir eventos sin límite de capacidad
                      ->orWhere(function ($query) {
                          $query->where('is_limited_capacity', 1) // Si tiene capacidad limitada
                                ->whereHas('eventUser', function ($query) {
                                    // Contar usuarios registrados y comparar con la capacidad del evento
                                    $query->havingRaw('COUNT(*) < events.capacity');
                                });
                      });
            })
            ->get()
            ->map(function ($event) {
                // Mostrar la imagen de tipo 'cover' si existe, si no, dejarlo en null
                $event->first_image = $event->images->isNotEmpty() ? $event->images->first()->image : null;
                return $event;
            });

            return view('user-event/home', compact('events'));

        }
    }


    public function show($id)
    {
        /*    
            //si el usuario esta logueado como admin se tiene que mostrar como sino estuviera logueado
            //aqui verificamos que hay acts en este evento para el usuario que sino hay se redirecciona al home
            //en dado caso de que el evento no tenga actividades para nadie todos pueden entrar
            //falta revisar el rol del usuario
            //si el rol es admin no se debe hacer tanta validacion, solo mostrar la informacion
            //si el rol es client si se hace toda la validacion 
        */
        if (auth()->check()) {// El usuario está autenticado
            //aqui si el usuario ya esta registrado lo redireccionamos a el home
            //revisamos si el rol del usuario es admin
            // Verificar si el usuario tiene el rol requerido
            $userLogued = Auth::user();
            if ($userLogued->role->name == 'admin') {
            
                return $this->showEvent($id);//mostramos el contenido como si no estuviera logueado

            }else if($userLogued->role->name == 'client'){//el usuario probablemente sea client
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
                    ->where('gender',$this->getUserData('gender')) // Filtrar por género(M,F)
                    ->orWhere('gender', 'Mix')//mostrar tambien las que sean mixtas
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
            }

        }else{//else para user no auth
            return $this->showEvent($id);//mostramos el contenido como si no estuviera logueado
        }
          
    }


    // Método para validar las actividades seleccionadas
    public function validateSelectedActivities($selectedActivities)
    {
        if ($selectedActivities == null || count($selectedActivities) == 0) {
            return redirect()->back()->withErrors(['error' => 'Necesitas seleccionar una actividad mínimo']);
        }
        if (count($selectedActivities) > 3) {
            return redirect()->back()->withErrors(['error' => 'Solo puedes seleccionar 3 actividades como máximo']);
        }
        return true;
    }

    // Método para inscripción gratuita
    public function inscriptionFree(Request $request, $id)
    {
        if (auth()->check()) { // Usuario autenticado
            $decryptedId = decrypt($id);
            $event = Event::findOrFail($decryptedId);

            
            // Obtener las actividades seleccionadas y desencriptarlas
            $acts = $request->input('activities', []);
            dd($acts);
            $selectedActivities = array_map(function ($encryptedActivity) {
                return Crypt::decrypt($encryptedActivity);
            }, $acts);
            
            // Llamar al método de validación
            $validation = $this->validateSelectedActivities($selectedActivities);
            if ($validation !== true) {
                return $validation; // Retornar el error si no es válido
            }

            if (!$event) {
                return redirect()->back()->withErrors(['error' => 'Valores incorrectos']);
            }

            if ($this->validateEventUser($id)) { // Validar que el usuario no esté inscrito
                return redirect()->route('home')->withErrors(['error' => 'Ya estás registrado en ese evento.']);
            }

            if ($this->validateRegistrationDeadLine($id)) { // Validar fecha límite
                return redirect()->route('home')->withErrors(['error' => 'Evento expirado']);
            }

            // Validar capacidad
            $capacityValidation = $this->validateCapacity($id);
            if ($capacityValidation == 'withoutlimit' || $capacityValidation == 'withcapacity') {
                $message = $this->inscription($id, $selectedActivities);
                return redirect()->route('home')->with('success', $message);
            } else if ($capacityValidation == 'withoutcapacity') {
                return redirect()->route('home')->withErrors(['error' => 'Evento agotado']);
            }
        } else { // Redireccionar si el usuario no está autenticado
            return view('auth/login');
        }
    }

    // Método para inscripción (con actividades o sin actividades)
    public function inscription($id, $selectedActivities)
    {
        $decryptedId = decrypt($id);
        $event = Event::findOrFail($decryptedId);

        if ($event->activities == 1) { // Si el evento tiene actividades
            // Llamar al método de validación
            $validation = $this->validateSelectedActivities($selectedActivities);
            if ($validation !== true) {
                return $validation; // Retornar el error si no es válido
            }

            // Validación y lógica de inscripción de actividades
            $userGender = $this->getUserData('gender');
            $subName = $this->getUserData('sub');

            $validActivities = ActivityEvent::where('event_id', $decryptedId)
                ->whereIn('activity_id', $selectedActivities)
                ->where(function ($query) use ($userGender) {
                    $query->where('gender', $userGender)
                          ->orWhere('gender', 'Mix');
                })
                ->whereHas('sub', function ($query) use ($subName) {
                    $query->where('name', 'LIKE', "%$subName%");
                })
                ->count();

            if ($validActivities != count($selectedActivities)) {
                return redirect()->back()->withErrors(['error' => 'Una o más actividades seleccionadas no son válidas para este usuario.']);
            }

            // Inserción en la tabla intermedia
            $user = auth()->user();
            $eventUser = EventUser::create([
                'user_id' => $user->id,
                'event_id' => $decryptedId,
            ]);

            // Insertar cada actividad en 'activity_event_users'
            foreach ($selectedActivities as $activityId) {
                ActivityEventUser::create([
                    'event_user_id' => $eventUser->id,
                    'activity_id' => $activityId,
                ]);
            }
            return 'Registro realizado exitosamente.';
        } else {
            // El evento no tiene actividades, solo inscribirse
            $user = auth()->user();
            $eventUser = EventUser::create([
                'user_id' => $user->id,
                'event_id' => $decryptedId,
            ]);
            return 'Registro realizado exitosamente.';
        }
    }

    // Método para obtener datos del usuario
    public function getUserData($valueOP)
    {
        $user = auth()->user();
        switch ($valueOP) {
            case 'gender':
                return $user->gender;

            case 'sub':
                $birthdate = $user->birthdate;
                $currentYear = Carbon::now()->year;
                $birthYear = Carbon::parse($birthdate)->year;
                $ageThisYear = $currentYear - $birthYear;
                return $ageThisYear;
        }
    }

    // Método para validar que un usuario no está inscrito a un evento
    public function validateEventUser($id)
    {
        $decryptedId = decrypt($id);
        $user = auth()->user();
        $eventUser = EventUser::where('user_id', $user->id)->where('event_id', $decryptedId)->first();
        return $eventUser ? true : false;
    }

    // Método para validar la capacidad del evento
    public function validateCapacity($id)
    {
        $decryptedId = decrypt($id);
        $event = Event::find($decryptedId);
        $limitedCapacity = $event->is_limited_capacity;

        if ($limitedCapacity) {
            $capacity = $event->capacity;
            $users = EventUser::where('event_id', $decryptedId)->count();
            if ($users < $capacity) {
                return 'withcapacity';
            } else {
                return 'withoutcapacity';
            }
        } else {
            return 'withoutlimit';
        }
    }

    // Método para validar la fecha límite de inscripción
    public function validateRegistrationDeadLine($id)
    {
        $decryptedId = decrypt($id);
        $event = Event::find($decryptedId);
        $registrationDeadline = $event->registration_deadline;

        if (Carbon::parse($registrationDeadline)->isPast()) {
            return true;
        }
        return false;
    }

    // Mostrar detalles del evento (sin cambios)
    public function showEvent($id)
    {
        $decryptedId = decrypt($id);
        $event = Event::findOrFail($decryptedId);
        $event1 = Event::with('places')->findOrFail($decryptedId);
        $places = $event1->places;
        $activities = ActivityEvent::where('event_id', $event->id)->with(['activity', 'sub'])->get();
        $eventIMG = Event::with('images')->findOrFail($decryptedId);

        $orderedImages = $eventIMG->images->sortBy(function ($image) {
            switch ($image->type) {
                case 'cover':
                    return 1;
                case 'kit':
                    return 2;
                case 'content':
                    return 3;
                default:
                    return 4;
            }
        });

        return view('user-event.show', compact('event', 'activities', 'places', 'orderedImages'));
    }
}
