<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Activity;
use App\Models\Sub;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()//ya
    {
        $data['events'] = Event::paginate(10);
        return view('event.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //hacer un carrusel de imgs en el sistema y que las pueda elegir o subir una nueva las que quiera en si 
        //insert into event, event_activities, img,img_events incluso en dado caso si quiero registrar una nueva actividad
        //se tiene que hacer una insercion en actividad antes que en evento(se haria antes que todas las demas inserciones)

        //aqui vamos a tener que mandar la lista de actividades para que las seleccione
        //show the activities registered
        $activities = Activity::all(); // Esto te da una colección de todos los modelos Activity
        //show the subs registered
        $subs = Sub::all(); // Esto te da una colección de todos las subs
        return view('event.create', compact('activities','subs'));        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datosEvento = $request->except('_token');

        if ($request->hasFile('image')) {
            $datosEvento['image'] = $request->file('image')->store('uploads', 'public');
        }

        //registras primero el evento y luego a que actividades estan registradas en el evento
        $event = Event::create($datosEvento);
        
        $id = $event->id;
        //aqui sigue guardar las actividades a realizar


        return redirect('event')->with('mensaje', 'Evento agregado con éxito');
    }

    // Resto de los métodos del controlador...
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('event.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
    
    $datosEvento = $request->except(['_token', '_method']);

    if ($request->hasFile('image')) {
        $evento = Event::findOrFail($id);
        // Store the new image
        $datosEvento['image'] = $request->file('image')->store('uploads', 'public');
    }

    // Update the database record with the new data
    Event::where('id', '=', $id)->update($datosEvento);

    return redirect('event')->with('mensaje', 'Evento Modificado');
}

/**
 * Display the specified resource.
 */
public function show($id)
{
    $evento = Event::findOrFail($id);
    return view('event.show', compact('event'));
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evento = Event::findOrFail($id);

        // Elimina el evento sin borrar la imagen
        Event::destroy($id);

        return redirect('event')->with('mensaje', 'Evento borrado');
    }
}
