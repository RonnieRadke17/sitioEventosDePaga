<?php

namespace App\Http\Controllers;

use App\Models\Event;
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
        return view('event.create');
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

        Event::create($datosEvento);

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
