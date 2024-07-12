<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Image;
use App\Models\Activity;
use App\Models\Sub;
use App\Models\ActivityEvent;
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
    
        $eventData = [
            'name' => $request->name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'price' => $request->price,
        ];

        //registras primero el evento y luego a que actividades estan registradas en el evento
        $event = Event::create($eventData);

        //luego guardamos el id del evento para registrar las imgs del evento
        $eventId = $event->id;

        #luego hacemos la insercion de las imagenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                //insercion de manera local de la img y en la DB
                $path = $image->store('uploads', 'public');
                $imageModel = Image::create([
                    'image' => $path
                ]);
                // vinculamos el id de la imagen con el id del evento 
                $event = Event::find($eventId);
                // Utilizando el método attach para asociar la imagen al evento
                $event->imgEvents()->attach($imageModel->id);
            }
        }

        
        try {
            // Iniciar una transacción para asegurar la integridad de los datos
            \DB::beginTransaction();

            // Obtener todas las actividades seleccionadas
            $selectedActivities = $request->input('selected_activities');

            foreach ($selectedActivities as $activityId) {
                // Obtener los géneros seleccionados para esta actividad
                $genders = $request->input("genders.$activityId", []);

                // Obtener las subactividades seleccionadas para esta actividad
                $subs = $request->input("subs.$activityId", []);

                // Iterar sobre los géneros seleccionados
                foreach ($genders as $gender => $value) {
                    // Iterar sobre las subactividades seleccionadas para este género
                    foreach ($subs[$gender] as $subId) {
                        // Crear un nuevo ActivityEvent
                        ActivityEvent::create([
                            'event_id' => $eventId,
                            'activity_id' => $activityId,
                            'gender' => $gender,
                            'sub_id' => $subId,
                        ]);
                    }
                }
            }

            // Commit de la transacción si todo está correcto
            \DB::commit();

            // Redireccionar o retornar una respuesta de éxito según tu flujo de aplicación
            return redirect()->route('event.index')->with('success', 'Actividades registradas correctamente.');
        } catch (\Exception $e) {
            // En caso de error, hacer rollback de la transacción y manejar el error
            \DB::rollback();
            // Imprimir la excepción para depuración
            dd($e);
            return back()->withInput()->withErrors(['error' => "Error"]);
        }

    }

    // Resto de los métodos del controlador...
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $activities = Activity::all();
        $subs = Sub::all();
        
        // Obtener las actividades del evento
        $eventActivities = ActivityEvent::where('event_id', $id)->get()->groupBy('activity_id');
    
        return view('event.edit', compact('event', 'activities', 'subs', 'eventActivities'));
    }
    


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Obtención de los datos del evento
        $eventData = $request->except(['_token', '_method', 'selected_activities', 'genders', 'subs', 'images']);
        
        // Actualización del evento
        $event = Event::findOrFail($id);
        $event->update($eventData);

        // Manejo de las imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Guardar la nueva imagen
                $path = $image->store('uploads', 'public');
                $imageModel = Image::create([
                    'image' => $path
                ]);
                // Vincular la nueva imagen con el evento
                $event->imgEvents()->attach($imageModel->id);
            }
        }

        // Manejo de actividades, géneros y subgéneros
        try {
            \DB::beginTransaction();

            // Obtener todas las actividades seleccionadas
            $selectedActivities = $request->input('selected_activities', []);

            // Limpiar relaciones actuales de actividades
            ActivityEvent::where('event_id', $id)->delete();

            foreach ($selectedActivities as $activityId) {
                // Obtener los géneros seleccionados para esta actividad
                $genders = $request->input("genders.$activityId", []);

                // Obtener las subactividades seleccionadas para esta actividad
                $subs = $request->input("subs.$activityId", []);

                foreach ($genders as $gender => $value) {
                    foreach ($subs[$gender] as $subId) {
                        ActivityEvent::create([
                            'event_id' => $id,
                            'activity_id' => $activityId,
                            'gender' => $gender,
                            'sub_id' => $subId,
                        ]);
                    }
                }
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withInput()->withErrors(['error' => "Error al actualizar las actividades: " . $e->getMessage()]);
        }

        return redirect()->route('event.index')->with('success', 'Evento modificado correctamente.');
    }


/**
 * Display the specified resource.
 */
    public function show($id)
    {
        //tenemos que buscar en la tabla intermedia las imagenes
        $event = Event::with('images')->findOrFail($id);
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
