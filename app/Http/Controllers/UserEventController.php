<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class UserEventController extends Controller
{
    public function index()//show all events
    {
         
        if (auth()->check()) {// El usuario está autenticado
            /* 
                mostrar por sub y genero y que no este registrado ya
                como a su vez que no aparescan los eventos si ya paso la fecha de registro
            */
            $user = auth()->user(); 
            $age = $user->birthdate;
            $gender = $user->gender;

            $events = Event::with('images')->get()->map(function ($event) {
                $event->first_image = $event->images->isNotEmpty() ? $event->images->first()->image : 'default.jpg';
                return $event;
            });
        
            return view('home', compact('events','age','gender'));
        
        } else {// El usuario no está autenticado mostrar todos los eventos//falta que si ya se paso la fecha no se muestre
            //mandar los eventos con las imgs
            $events = Event::with('images')->get()->map(function ($event) {
                $event->first_image = $event->images->isNotEmpty() ? $event->images->first()->image : 'default.jpg';
                return $event;
            });
        
            return view('home', compact('events'));
        }
    }

    public function events($id)//show specific event
    {
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
