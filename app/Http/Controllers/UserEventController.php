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
        // Obtener el usuario autenticado
            $user = auth()->user(); 
            $age = $user->birthdate; // Fecha de cumpleaños
            $gender = $user->gender; // Género
            $birthdate = $user->birthdate; // Suponiendo que 'birthdate' es una fecha válida en formato 'YYYY-MM-DD'
            $currentYear = Carbon::now()->year; // Obtén el año actual
            
            // Obtener el año de nacimiento
            $birthYear = Carbon::parse($birthdate)->year;
            
            // Calcular la edad que el usuario va a tener o tiene en el año vigente
            $ageThisYear = $currentYear - $birthYear;            
            
            // Parte del nombre del sub que quieres comparar
            $subName = $ageThisYear; 
            
            // Filtrar los ActivityEvents donde el usuario puede participar
            $activityEventIds = ActivityEvent::where('gender', $gender)
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
            return view('home', compact('events','ageThisYear','gender','activityEventIds'));
            

        
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


        //retorna cuenta de eventos que si tiene los datos del user y mostrar esos eventos por id
    }

    /* public function show($id)//show specific event cambiar nombre a show
    {
        //si el usuario no esta logueado mostrar todas las acts del evento
        //si esta logueado solo mostrar en las que esta logueado
        //mostrar ubicacion, acts y imgs
        $decryptedId = decrypt($id);
        $event = Event::findOrFail($decryptedId);
        
        return view('user-event.show', compact('event'));
    } */


    public function show($id)
    {
        // Desencriptar el ID del evento
        $decryptedId = decrypt($id);
        // Buscar el evento con sus actividades y sus relaciones en activity_events
        $event = Event::findOrFail($decryptedId);

        // Buscar el evento con sus relaciones (lugares en este caso)
        $event1 = Event::with('places')->findOrFail($decryptedId)->first();
        // Obtener los lugares relacionados al evento
        $places = $event1->places;

        // Obtener todas las actividades del evento con sus géneros y subs correspondientes
        $activities = ActivityEvent::where('event_id', $event->id)
            ->with(['activity', 'sub']) // Cargar la actividad y la sub
            ->get();
        

        // Devolver la vista con el evento y las actividades
        return view('user-event.show', compact('event', 'activities','places'));
    }


    





    public function purchase($id)//show specific event este no sirve horita para nada
    {
        $event = Event::findOrFail($id);
        return view('user-event.purchase', compact('event'));
    }


    //metodo atach para poder hacer el registro
    //pero de eso depende
}
