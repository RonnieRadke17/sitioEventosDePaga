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

        // Obtener los eventos correspondientes a esos ActivityEvents y aplicar otras condiciones
        $events = Event::with(['images' => function($query) {
                        $query->where('type', 'cover'); // Solo obtener imágenes de tipo 'cover'
                    }])
                    ->where('registration_deadline', '>', now()) // Filtrar eventos cuya fecha límite de registro no haya pasado
                    ->whereIn('id', $activityEventIds) // Filtrar solo los eventos en los que el usuario puede participar
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

    public function events($id)//show specific event cambiar nombre a show
    {
        //mandar las actividades del evento que sean compatibles con el usuario, revisar el caso de que no este
        //logueado
        $event = Event::findOrFail($id);
        return view('user-event.show', compact('event'));
    }

    public function purchase($id)//show specific event este no sirve horita para nada
    {
        $event = Event::findOrFail($id);
        return view('user-event.purchase', compact('event'));
    }


    //metodo atach para poder hacer el registro
    //pero de eso depende
}
