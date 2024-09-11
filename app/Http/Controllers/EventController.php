<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Image;
use App\Models\Activity;
use App\Models\Sub;
use App\Models\Place;
use App\Models\ActivityEvent;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//aqui definimos el controlador del mapa para el evento
use App\Http\Controllers\MapEventController;


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
        $images = Image::all();//mandamos todas las imagenes que se tienen
        $subs = Sub::all(); // Esto te da una colección de todos las subs
        $places = Place::all();
        $activities = Activity::all();
        return view('event.create', compact('activities','subs','places','images'));         
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
            'price' => 'required|numeric|min:10|max:10000',
            /* 
                aqui validamos la informacion del mapa para si esta mal retornar los mensajes de error
                aqui se valida si se selecciono algun lugar ya registrado para validarlo
                validar si hay algun lugar seleccionado 
            */
            'place_id' => [
            'required',
            'string',
            'max:60',
            //aqui validamos la informacion del mapa 
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === 'Otro') {
                        if (empty($request->input('place'))) {
                            $fail('The place field is required when place_id is Otro.');
                        }
                        if (empty($request->input('address'))) {
                            $fail('The address field is required when place_id is Otro.');
                        }
                        if (!preg_match('/^-?\d{1,2}\.\d+$/', $request->input('lat'))) {
                            $fail('The lat field must be a valid decimal when place_id is Otro.');
                        }
                        if (!preg_match('/^-?\d{1,3}\.\d+$/', $request->input('lng'))) {
                            $fail('The lng field must be a valid decimal when place_id is Otro.');
                        }
                    }
                },
            ],

            //aqui validamos la informacion de las actividades
            'is_with_activities' =>[
                'required',
                'boolean',
                'in:0,1',
                //aqui validamos la informacion del mapa 
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === 1) {//validamos si las actividades estan bien
                        
                    }
                },
            ],

            /*
                aqui validamos la informacion de las imagenes 
                es una relacion de 1-m por eso cada evento tiene sus imagenes
            */
            'cover' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
            ],
            'kit' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
            ],
            'images.*' => [//content
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
            ],

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
                'activities' => $request->is_with_activities,//este campo es de si es con actividades el evento o no
                'price'=> $request->price
            ]);
            
            //hacemos la incersion del lugar y lo vinculamos con el evento
            if ($request->place_id == 'Otro') {
                
                $place = Place::create([
                    'name' => $request->place,
                    'address' => $request->address,
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                ]);
                //aqui hacemos la vinculacion del lugar con el evento
                $event->places()->attach($place->id);

            }else{//aqui validamos la informacion del id del lugar para hacer la vinculacion con el evento
                //en la tabla intermedia de event_places
                $event->places()->attach($request->place_id);
            }
        
            /* aqui hacemos la incersion de las actividades del evento en la tabla intermedia activity_event
                Obtener todas las actividades seleccionadas
                aqui falta poner la opcion de con o sin actividades del evento eso involucra el enum de la DB
                //aqui hacemos la incersion de las actividades del evento en la tabla intermedia activity_event
                // Obtener todas las actividades seleccionadas
                //aqui falta poner la opcion de con o sin actividades del evento eso involucra el enum de la DB
             */
            if($request->is_with_activities == 1){//comparamos  que el valor dentro sea int,string
                try {
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
                                    'event_id' => $event->id,//id del evento no uso atach porque se mandan mas campos
                                    'activity_id' => $activityId,
                                    'gender' => $gender,
                                    'sub_id' => $subId,
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Error inserting activity event: ' . $e->getMessage());
                }
                
            }

            //imagenes del evento
            if ($request->hasFile('cover')) {//first image
                $image = $request->file('cover');  
                $path = $image->store('uploads', 'public'); 
                $imageModel = Image::create([
                    'image' => $path,
                    'event_id' => $event->id,
                    'type' => 'cover'
                ]);
            }

            if ($request->hasFile('kit')) {//kit
                $image = $request->file('kit');  
                $path = $image->store('uploads', 'public'); 
                $imageModel = Image::create([
                    'image' => $path,
                    'event_id' => $event->id,
                    'type' => 'kit'
                ]);
            }

            if ($request->hasFile('images')) {//content
                foreach ($request->file('images') as $image) {
                    //insercion de manera local de la img y en la DB
                    $path = $image->store('uploads', 'public');
                    $imageModel = Image::create([
                        'image' => $path,
                        //aqui va el id del evento
                        'event_id' => $event->id,
                        'type' => 'content'
                    ]);
                }
            }

            return redirect()->route('event.index');
        }
        
    }

    // faltan las imgs
    public function edit($id)
    {
        $decryptedId = decrypt($id);
        $event = Event::findOrFail($decryptedId);
        $places = Place::all();
        
        // Obtener el primer lugar asociado al evento
        $eventPlaceId = $event->places()->pluck('place_id')->first(); // Obtener el ID del lugar asociado al evento
    
        $activities = Activity::all();
        $subs = Sub::all();
        
         // Obtener las actividades asociadas al evento, agrupadas por activity_id
        $eventActivities = ActivityEvent::where('event_id', $decryptedId)
        ->get()
        ->groupBy('activity_id'); // Agrupa las actividades por activity_id
        //imagenes seleccionadas

        // Obtener imágenes del evento por tipo
        $coverImage = $event->images()->where('type', 'cover')->first();
        $kitImage = $event->images()->where('type', 'kit')->first();
        $contentImages = $event->images()->where('type', 'content')->get();
    
        return view('event.edit', compact('event','eventPlaceId', 'activities', 'subs', 'places','eventActivities','coverImage', 'kitImage', 'contentImages'));
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
            'price' => 'required|numeric|min:10|max:10000',
            'place_id' => [
                'required',
                'string',
                'max:60',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === 'Otro') {
                        if (empty($request->input('place'))) {
                            $fail('The place field is required when place_id is Otro.');
                        }
                        if (empty($request->input('address'))) {
                            $fail('The address field is required when place_id is Otro.');
                        }
                        if (!preg_match('/^-?\d{1,2}\.\d+$/', $request->input('lat'))) {
                            $fail('The lat field must be a valid decimal when place_id is Otro.');
                        }
                        if (!preg_match('/^-?\d{1,3}\.\d+$/', $request->input('lng'))) {
                            $fail('The lng field must be a valid decimal when place_id is Otro.');
                        }
                    }
                },
            ],
            'is_with_activities' => ['required', 'boolean', 'in:0,1'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'kit' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
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

        //actualizacion del lugar

        // Actualizamos la vinculación del lugar si es necesario
        if ($request->place_id == 'Otro') {
            // Actualizamos o creamos un nuevo lugar
            $place = Place::updateOrCreate(
                ['id' => $event->places()->pluck('place_id')->first()],
                [
                    'name' => $request->place,
                    'address' => $request->address,
                    'lat' => $request->lat,
                    'lng' => $request->lng
                ]
            );
            $event->places()->sync([$place->id]);
        } else if ($event->places()->pluck('place_id')->first() != $request->place_id) {
            // Vinculamos el lugar existente solo si es diferente
            $event->places()->sync([$request->place_id]);
        }

        //actualizacion de las actividades
        // Actualizamos las actividades del evento si es necesario
        if ($request->is_with_activities == 1) {
            // Obtener las actividades actualmente asociadas con el evento
            $currentActivities = ActivityEvent::where('event_id', $event->id)
                ->get(['activity_id', 'gender', 'sub_id'])
                ->map(function ($activityEvent) {
                    return [
                        'activity_id' => $activityEvent->activity_id,
                        'gender' => $activityEvent->gender,
                        'sub_id' => $activityEvent->sub_id
                    ];
                })->toArray();

            // Preparar las actividades nuevas del request
            $newActivities = [];
            $selectedActivities = $request->input('selected_activities', []);
            foreach ($selectedActivities as $activityId) {
                $genders = $request->input("genders.$activityId", []);
                $subs = $request->input("subs.$activityId", []);
                foreach ($genders as $gender => $value) {
                    foreach ($subs[$gender] as $subId) {
                        $newActivities[] = [
                            'activity_id' => $activityId,
                            'gender' => $gender,
                            'sub_id' => $subId
                        ];
                    }
                }
            }

            // Convertir las actividades a cadenas JSON para comparar fácilmente
            $currentActivitiesCollection = collect($currentActivities)->map(function ($activity) {
                return json_encode($activity);  // Convertir cada actividad a JSON
            });

            $newActivitiesCollection = collect($newActivities)->map(function ($activity) {
                return json_encode($activity);  // Convertir cada nueva actividad a JSON
            });

            // Encontrar actividades que ya no están en la nueva solicitud (deben eliminarse)
            $activitiesToDelete = $currentActivitiesCollection->diff($newActivitiesCollection);
            foreach ($activitiesToDelete as $activity) {
                $activity = json_decode($activity, true);  // Decodificar la actividad a su forma original
                ActivityEvent::where('event_id', $event->id)
                    ->where('activity_id', $activity['activity_id'])
                    ->where('gender', $activity['gender'])
                    ->where('sub_id', $activity['sub_id'])
                    ->delete();
            }

            // Encontrar actividades que no estaban antes (deben agregarse)
            $activitiesToAdd = $newActivitiesCollection->diff($currentActivitiesCollection);
            foreach ($activitiesToAdd as $activity) {
                $activity = json_decode($activity, true);  // Decodificar la actividad a su forma original
                ActivityEvent::create([
                    'event_id' => $event->id,
                    'activity_id' => $activity['activity_id'],
                    'gender' => $activity['gender'],
                    'sub_id' => $activity['sub_id']
                ]);
            }
        }
        
        //actualizacion de las imagenes

        // Actualizamos las imágenes del evento
        if ($request->hasFile('cover')) {
            $image = $request->file('cover');
            $path = $image->store('uploads', 'public');
            Image::updateOrCreate(
                ['event_id' => $event->id, 'type' => 'cover'],
                ['image' => $path]
            );
        }

        if ($request->hasFile('kit')) {
            $image = $request->file('kit');
            $path = $image->store('uploads', 'public');
            Image::updateOrCreate(
                ['event_id' => $event->id, 'type' => 'kit'],
                ['image' => $path]
            );
        }

        Image::where('event_id', $event->id)
                    ->where('type','content')
                    ->delete();
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('uploads', 'public');
                Image::create([
                    'image' => $path,
                    'event_id' => $event->id,
                    'type' => 'content'
                ]);
            }
        }
        //falta redireccion con mesaje de exito
        return redirect()->route('event.index');
    }

    public function show($id)
    {
        //tenemos que buscar en la tabla intermedia las imagenes
        $decryptedId = decrypt($id);
        $event = Event::findOrFail($decryptedId);
        return view('event.show', compact('event'));
        //$images = Image::all();//mandamos todas las imagenes que se tienen
        //$images = Image::all()->chunk(3);
        //$subs = Sub::all(); // Esto te da una colección de todos las subs
        //obtenemos todas las categorias con las actividades
        //$places = Place::all();
        //$activityCategories = ActivityCategory::with('activities')->get();
        //$activities = Activity::all();
        
        //return view('event.create', compact('activities','subs','places','images'));  
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        // Elimina el evento sin borrar la imagen
        Event::destroy($id);

        return redirect('event')->with('mensaje', 'Evento borrado');
    }
}
