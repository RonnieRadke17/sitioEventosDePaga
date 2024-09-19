<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ActivityEvent;
use App\Models\EventUser;
use App\Models\PaymentRequest;
use App\Models\ActivityEventUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;

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
        session()->forget('activities');
        session()->forget('id');
        try {
            // Desencriptar el event_id
            $decryptedId = decrypt($id);
            //$event = Event::findOrFail($decryptedId);
        } catch (DecryptException $e) {
            // Error en la desencriptación
            return redirect()->route('home')->withErrors(['error' => 'Error al acceder a ese evento.']);
            
        }

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
            if($userLogued->role->name == 'client' || $userLogued->role->name == 'admin'){//el usuario probablemente sea client
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

    // Mostrar detalles del evento (sin cambios)
    public function showEvent($id)
    {
        try {
            // Desencriptar el event_id
            $decryptedId = decrypt($id);
            //$event = Event::findOrFail($decryptedId);
        } catch (DecryptException $e) {
            // Error en la desencriptación
            return redirect()->route('home')->withErrors(['error' => 'Error al acceder a ese evento.']);
            
        }
        //$decryptedId = decrypt($id);
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

    public function validateActivities(Request $request, $id)
    {
        try {
            // Desencriptar el event_id
            $decryptedId = decrypt($id);
            //$event = Event::findOrFail($decryptedId);
        } catch (DecryptException $e) {
            // Error en la desencriptación
            throw ValidationException::withMessages([
                'event' => 'Error en el evento',
            ]);
        }
        $event = Event::findOrFail($decryptedId);
        $result = true;

        // Validar que al menos una actividad ha sido seleccionada
        // Validar que al menos una actividad ha sido seleccionada y que no excede el máximo permitido
        $request->validate([
            'activities' => 'required|array|max:3', //maximo 3
        ], [
            'activities.required' => 'Debes seleccionar al menos una actividad.',
            'activities.max' => 'No puedes seleccionar más de 3 actividades.',
        ]);
            
        // Recorrer el array de actividades seleccionadas
        foreach ($request->input('activities') as $encryptedActivityId => $genders) {
            foreach ($genders as $encryptedGender => $subIds) {
                foreach ($subIds as $encryptedSubId => $value) {
                    if ($value == 'on') {  // Verificar si el checkbox fue marcado
                        try {
                            // Desencriptar el activity_id, gender y sub_id
                            $activityId = Crypt::decrypt($encryptedActivityId);
                            $gender = Crypt::decrypt($encryptedGender);
                            $subId = Crypt::decrypt($encryptedSubId);

                            // Validación de la existencia de la actividad en el evento
                            $exists = ActivityEvent::where('event_id', $decryptedId)
                                        ->where('activity_id', $activityId)
                                        ->where('gender', $gender)
                                        ->where('sub_id', $subId)
                                        ->exists();

                            if (!$exists) {
                                return false; // La actividad no es válida
                            }

                        } catch (DecryptException $e) {
                            // Error en la desencriptación
                            throw ValidationException::withMessages([
                                'activities' => 'Error en las actividades.',
                            ]);
                        }
                    }
                }
            }
        }

        // Si todo es válido, retornamos verdadero
        return $result;
    }

    // Método para inscripción gratuita
    public function inscriptionFree(Request $request, $id)
    {
        if (auth()->check()) { // Usuario autenticado
                
            if ($this->validateEventUser($id)) { // Validar que el usuario no esté inscrito
                return redirect()->route('home')->withErrors(['error' => 'Ya estás registrado en ese evento.']);
            }
    
            if ($this->validateRegistrationDeadLine($id)) { // Validar fecha límite
                return redirect()->route('home')->withErrors(['error' => 'Evento expirado']);
            }
    
            // Validar capacidad
            $capacityValidation = $this->validateCapacity($id);
            if ($capacityValidation == 'withoutlimit' || $capacityValidation == 'withcapacity') {
                $message = $this->inscription($request,$id);
                return redirect()->route('home')->with('success', $message);
            } else if ($capacityValidation == 'withoutcapacity') {
                return redirect()->route('home')->withErrors(['error' => 'Evento agotado']);
            }
            
        } else { // Redireccionar si el usuario no está autenticado
            return view('auth/login');
        }
    }

    //metodo de inscripcion
    public function inscription(Request $request, $id)
    {
        $decryptedId = decrypt($id);
        $event = Event::findOrFail($decryptedId);

        if ($event->activities == 1) { // Si el evento tiene actividades

            // Llamar al método de validación para validar las actividades seleccionadas
            $validation = $this->validateActivities($request, $id);

            if (!$validation) { // Si las actividades NO son validas es porque el usuario cambio algun valor de la act,gender,sub
                return redirect()->route('home')->withErrors(['error' => 'Una o más actividades seleccionadas no son válidas.']);
            }else{

                // Obtener el género y la sub del usuario
            $userGender = $this->getUserData('gender'); // Método que obtiene el género del usuario ('M' o 'F')
            $subName = $this->getUserData('sub'); // Método que obtiene la sub del usuario

            // Inserción en la tabla intermedia para la inscripción
            $user = auth()->user();
            $eventUser = EventUser::create([
                'user_id' => $user->id,
                'event_id' => $decryptedId,
            ]);

            // Recorrer las actividades seleccionadas y desencriptarlas
            foreach ($request->input('activities') as $encryptedActivityId => $genders) {
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

            return 'Registro realizado exitosamente.';
            }

            
        } else { // El evento no tiene actividades, solo inscribirse
           
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
        try {
            // Desencriptar el event_id
            $decryptedId = decrypt($id);
            //$event = Event::findOrFail($decryptedId);
        } catch (DecryptException $e) {
            // Error en la desencriptación
            throw ValidationException::withMessages([
                'event' => 'Error en el evento',
            ]);
        }
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


    //metodo para confirmar la la compra y revisar que esta validado todo
    //se tiene que validar las acts si hay, que estan bien validadas y que el evento no este inscrito que no este expirado y todas esas cuestiones
    public function confirmPayment(Request $request,$id)
    {
        /* 
            $event = Event::find($request->event_id);
            $event->is_paid = true;
            $event->save();
            return response()->json(['message' => 'Pago confirmado']); 
        */
        //validamos que la informacion del evento siga siendo valida
        $activities = $request->input('activities');
        
        try {
            // Desencriptar el event_id
            $decryptedId = decrypt($id);
            //$event = Event::findOrFail($decryptedId);
        } catch (DecryptException $e) {
            // Error en la desencriptación
            return redirect()->route('home')->withErrors(['error' => 'Error al acceder a ese evento.']);
        }
        if ($this->validateEventUser($id)) { // Validar que el usuario no esté inscrito
            return redirect()->route('home')->withErrors(['error' => 'Ya estás registrado en ese evento.']);
        }

        if ($this->validateRegistrationDeadLine($id)) { // Validar fecha límite
            return redirect()->route('home')->withErrors(['error' => 'Evento expirado']);
        }

        // Validar capacidad
        $capacityValidation = $this->validateCapacity($id);

        if ($capacityValidation == 'withoutcapacity') {
            
            return redirect()->route('home')->withErrors(['error' => 'Evento agotado']);
        }

        $event = Event::findOrFail($decryptedId);
        //mandar valores de compra a la ventana
        $event1 = Event::with('places')->findOrFail($decryptedId);
        $places = $event1->places;
        $eventIMG = Event::with('images')->findOrFail($decryptedId);
        $orderedImages = $eventIMG->images->sortBy(function ($image) {
            switch ($image->type) {
                case 'cover':
                    return 1;
            }
        });
        if ($event->activities == 1) { // Si el evento tiene actividades
        
            $validation = $this->validateActivities($request, $id);

            if (!$validation) { // Si las actividades NO son validas es porque el usuario cambio algun valor de la act,gender,sub
                return redirect()->back()->withErrors(['error' => 'Una o más actividades seleccionadas no son válidas.']);
            }else{
                //crear orden de pago
                PaymentRequest::create([
                    'user_id' => Auth::id(),
                    'event_id' => $decryptedId,
                    //status por default es in process
                    'expiration' => Carbon::now()->addMinutes(15),
                ]);
            }


        }else{//crear orden de pago
            PaymentRequest::create([
                'user_id' => Auth::id(),
                'event_id' => $decryptedId,
                //status por default es in process
                'expiration' => Carbon::now()->addMinutes(15),
            ]);
        }

         // Almacena los datos en la sesión

    $request->session()->put('activities', $activities);
    session()->put('id', $decryptedId);
    

        return view('user-event/buy', compact('event','places','orderedImages'));

    }

}
