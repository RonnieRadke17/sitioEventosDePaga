<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Image;
use App\Models\ActivityEvent;
use App\Models\EventPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;



class EventController extends Controller
{

    public function index()
    {
        // Obtener los eventos con la primera imagen relacionada
        $events = Event::with('images')->get()->map(function ($event) {
            $event->first_image = $event->images->isNotEmpty() ? $event->images->first()->image : 'default.jpg';
            return $event;
        });

        return view('event.index', compact('events'));
    }

    public function create()
    {         
        return view('event.create');         
    }

    public function store(Request $request)//ya
    {
        
        $eventData = Validator::make($request->all(), [//validamos la informacion del evento y si esta mal retornamos el error especifico por cada campo
            'name' => 'required|string|max:60|unique:events,name',
            'description' => 'required|string|max:200',
            'event_date' => 'required|date|after:today',
            'registration_deadline' => 'required|date|after:today|before:event_date',
            'capacity' => 'nullable|numeric|min:5|max:900000',
            'price' => 'nullable|numeric|min:10|max:10000', 
        ]);

        if ($eventData->fails()) {//retornamos los errores de los datos del evento
            return redirect()->back()->withErrors($eventData)->withInput();
        }
        
        try {//registra el evento en la base de datos
            $event = DB::transaction(function () use ($request) {
                return Event::create([
                    'name' => $request->name,
                    'description'=> $request->description,
                    'event_date' => $request->event_date,
                    'registration_deadline' => $request->registration_deadline, 
                    'capacity' => $request->capacity ? $request->capacity : null,
                    'price'=> $request->price ? $request->price : null,
                ]);
            });

            if($event){//verifica el valor de la variable $event si tiene algo dentro lo redirecciona a la ruta de show
                $encryptedId = encrypt($event->id);
                return redirect()->route('event.show', $encryptedId)->with('success', 'Evento creado correctamente.');//redireccionar a ruta de show
            }else {//sino redirecciona a la ruta de event con un mensaje de error
                return redirect()->route('event')->withErrors(['error' => 'Ha ocurrido un error al crear el evento.']);
            }

        } catch (\Exception $e) {
            return redirect()->route('event')->withErrors(['error' => 'Ha ocurrido un error al crear el evento.']);
        }

    }


    public function edit($id)
    {
        $decryptedId = decrypt($id);
        $event = Event::findOrFail($decryptedId);
        return view('event.edit', compact('event'));
    }
    
    public function update(Request $request, $id)
    {
        // Desencriptamos el ID si es necesario
        $decryptedId = decrypt($id);
        // Buscar el evento que se va a actualizar
        $event = Event::findOrFail($decryptedId);

        // Validar los datos del formulario
        $eventData = Validator::make($request->all(), [
            'name' => 'required|string|max:60',
            'description' => 'required|string|max:200',
            'event_date' => 'required|date|after:today',
            'registration_deadline' => 'required|date|after:today|before:event_date',
            'capacity' => 'nullable|numeric|min:5|max:900000',
            'price' => 'nullable|numeric|min:10|max:10000',
            'status' => 'required|in:Activo,Inactivo,Cancelado',
        ]);

        if ($eventData->fails()) {
            return redirect()->back()->withErrors($eventData)->withInput();
        }


        //verificar aqui, si el evento esta cancelado, no se puede actualizar
        //verificar aqui, si el evento esta inactivo, no se puede actualizar
        //verificar el aspecto de la capacidad, revisar la cantidad de usuarios ingresados es decir que no sea menor a los que estan ingresados
        //revisar las fechas, si ya hay registros replantear el mandar correo de reagendacion del evento oy/o fechas de registro
        


        
        //actualizacion de campos de eventData
        $updates = [];
        if ($event->name !== $request->name) {
            $updates['name'] = $request->name;
        }
        if ($event->description !== $request->description) {
            $updates['description'] = $request->description;
        }
        if ($event->event_date !== $request->event_date) {
            $updates['event_date'] = $request->event_date;
        }
        if ($event->registration_deadline !== $request->registration_deadline) {
            $updates['registration_deadline'] = $request->registration_deadline;
        }
        
        if ($event->capacity !== $request->capacity) {
            $updates['capacity'] = $request->capacity;
        } else {
            $updates['capacity'] = null;
        }

        if ($event->price !== $request->price) {
            $updates['price'] = $request->price;
        }

        if ($event->status !== $request->status) {
            $updates['status'] = $request->status;
        }


        // Si hay cambios, actualizamos
        if (!empty($updates)) {
            $event->update($updates);
        }
        //falta redireccion con mesaje de exito
        return redirect()->route('event.index');
    }

    public function show($id)
    {
        // Desencriptar el ID del evento
        $decryptedId = decrypt($id);
        // Buscar el evento con sus actividades y sus relaciones en activity_events
        $event = Event::findOrFail($decryptedId);
        //hacer consulta de si encuentra registros ligados al evento
        $activitiesEvent = ActivityEvent::where('event_id', $event->id)->exists();
        $eventPlace = EventPlace::where('event_id', $event->id)->exists();
        $eventImages = Image::where('event_id', $event->id)->exists();
        return view('event.show', compact('event','activitiesEvent',"eventPlace","eventImages"));
    }

    public function destroy($id)
    {
        $decryptedId = decrypt($id);
        //borramos las imagenes las acts y el evento, falta borrar el registro del lugar ingermedio
        $event = Event::findOrFail($decryptedId);
        Image::where('event_id', $event->id)->delete();
        ActivityEvent::where('event_id', $event->id)->delete();
        EventPlace::where('event_id', $event->id)->delete();
        Event::where('id',$decryptedId)->delete();
        return redirect('event')->with('mensaje', 'Evento borrado');
    }

}
