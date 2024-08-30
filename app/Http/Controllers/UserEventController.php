<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class UserEventController extends Controller
{
    public function index()//show all events
    {
        //mandar los eventos con las imgs
        $events = Event::with('images')->get()->map(function ($event) {
            $event->first_image = $event->images->isNotEmpty() ? $event->images->first()->image : 'default.jpg';
            return $event;
        });
        return view('home', compact('events'));
        /* $data['events'] = Event::paginate(10);
        return view('home', $data); */
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
