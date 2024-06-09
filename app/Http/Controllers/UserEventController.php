<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class UserEventController extends Controller
{
    public function index()//show all events
    {
        $data['events'] = Event::paginate(10);
        return view('home', $data);
    }

    public function show($id)//show specific event
    {
        $event = Event::findOrFail($id);
        return view('user-event.show', compact('event'));
    }

    public function purchase($id)//show specific event
    {
        $event = Event::findOrFail($id);
        return view('user-event.purchase', compact('event'));
    }


    //metodo atach para poder hacer el registro
    //pero de eso depende
}
