<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Image;
//use App\Models\Activity;
//use App\Models\Sub;
//use App\Models\Place;
use App\Models\ActivityEvent;
//use App\Models\ActivityCategory;
use App\Models\EventPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



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
        return view('event.create-data');         
    }

    public function store(Request $request)
    {
        //dd($request);
        //validamos la informacion del evento y si esta mal retornamos el error especifico por cada campo
        $eventData = Validator::make($request->all(), [
            'name' => 'required|string|max:60',
            'description' => 'required|string|max:200',
            'event_date' => 'required|date|after:today',
            'kit_delivery' => 'nullable|date|after:today|before:event_date',//kit_delivery puede ser nulo pero sino es nulo se valida//la entrega de kits y restration sea after:today es decir se entregan despues de hoy
            'registration_deadline' => 'required|date|after:today|before:event_date',
            'is_limited_capacity' => 'required|boolean',
            'capacity' => [
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('is_limited_capacity') == 1) { // = true
                        if (is_null($request->input('capacity'))) {
                            $fail('The capacity field is required when limited capacity is enabled and must be greater than 0.');
                        }
                        if (!is_numeric($request->input('capacity')) || intval($request->input('capacity')) != $request->input('capacity')) {
                            $fail('The capacity must be an integer.');
                        }
                        if ($request->input('capacity') < 5 || $request->input('capacity') > 15000) {
                            $fail('The capacity is not valid.');
                        }
                    } else { // = false
                        if (!is_null($request->input('capacity'))) {
                            $fail('The capacity must be null when limited capacity is disabled.');
                        }
                    }
                },
            ],
            'price' => 'nullable|numeric|min:10|max:10000',
            
        ]);

        if ($eventData->fails()) {//retornamos los errores de los datos del evento
            return redirect()->back()
                ->withErrors($eventData)
                ->withInput();
        }else{
        
            //aqui falta un boolean de si es con actividades o no
            $event = Event::create([//registra primero el evento
                'name' => $request->name,
                'description'=> $request->description,
                'event_date' => $request->event_date,
                'kit_delivery' => $request->kit_delivery,
                'registration_deadline' => $request->registration_deadline, 
                'is_limited_capacity'=> $request->is_limited_capacity,
                'capacity' => $request->is_limited_capacity ? $request->capacity : null,
                'activities' => 0,//este campo es de si es con actividades el evento o no
                'price'=> $request->price
            ]);
            
            //$event->save();
            $encryptedId = encrypt($event->id);
            
            return redirect()->route('event.show', $encryptedId);//redireccionar a ruta de show
        }
        
    }


    public function edit($id)
    {
        $decryptedId = decrypt($id);
        $event = Event::findOrFail($decryptedId);
        return view('event.edit-data', compact('event'));
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
            'kit_delivery' => 'nullable|date|after:today|before:event_date',
            'registration_deadline' => 'required|date|after:today|before:event_date',
            'is_limited_capacity' => 'required|boolean',
            'capacity' => [
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('is_limited_capacity') == 1) {
                        if (is_null($request->input('capacity'))) {
                            $fail('The capacity field is required when limited capacity is enabled and must be greater than 0.');
                        }
                        if (!is_numeric($request->input('capacity')) || intval($request->input('capacity')) != $request->input('capacity')) {
                            $fail('The capacity must be an integer.');
                        }
                        if ($request->input('capacity') < 5 || $request->input('capacity') > 15000) {
                            $fail('The capacity is not valid.');
                        }
                    } else {
                        if (!is_null($request->input('capacity'))) {
                            $fail('The capacity must be null when limited capacity is disabled.');
                        }
                    }
                },
            ],
            'price' => 'nullable|numeric|min:10|max:10000',
        ]);

        if ($eventData->fails()) {
            return redirect()->back()->withErrors($eventData)->withInput();
        }
        
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
        if ($event->kit_delivery !== $request->kit_delivery) {
            $updates['kit_delivery'] = $request->kit_delivery;
        }
        if ($event->registration_deadline !== $request->registration_deadline) {
            $updates['registration_deadline'] = $request->registration_deadline;
        }
        if ($event->is_limited_capacity !== $request->is_limited_capacity) {
            $updates['is_limited_capacity'] = $request->is_limited_capacity;
            // Si cambia la capacidad, actualizamos también
            if ($request->is_limited_capacity == 1 && $event->capacity !== $request->capacity) {
                $updates['capacity'] = $request->capacity;
            } else {
                $updates['capacity'] = null;
            }
        }

        if ($event->price !== $request->price) {
            $updates['price'] = $request->price;
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
        $activitiesEvent = ActivityEvent::where('event_id', $event)->exists();
        $eventPlace = EventPlace::where('event_id', $event)->exists();
        $eventImages = Image::where('event_id', $event)->exists();

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
