<?php

namespace App\Http\Controllers;
use App\Models\Place;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventMapController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)/* ya */
    {
        $response = $this->validationView($id);
        if($response != null){
            return redirect()->back()->withErrors($response);
        }

        $places = Place::all();
        return view('event-map.create', compact('id', 'places'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)/* ya */
    {
        $this->validation($request);//llamamos al metodo de validacion

        try {//registra el evento en la base de datos
            $result = DB::transaction(function () use ($request) {
                $event = Event::find(Crypt::decrypt($request->id)); // Busca el evento con ID 1
                $place = Place::find(Crypt::decrypt($request->option)); // Busca el lugar con ID 2
                
                if($request->option == 'new'){
                     $place = Place::create([
                        'name' => $request->name,
                        'address' => $request->address,
                        'lat' => $request->lat,
                        'lon' => $request->lon,
                    ]);
                    //metoto attach que vincula el lugar con el evento
                    $event->places()->attach($place->id); // Asigna el lugar al evento
                    return true;
                }else{//se debe a que si el valor no es new es porque el valor que se esta comparando esta encriptado y por lo tanto es un id de lugar
                    /* aqui va el metodo attach que vincula el lugar con el evento */
                    $event->places()->attach($place->id); // Asigna el lugar al evento
                    return true;
                }
            });

            if($result){//si la transaccion fue exitosa
                return redirect()->route('event.show', $request->id)->with('success', 'Lugar asignado correctamente.');//redireccionar a ruta de show
            }else {//sino redirecciona a la ruta de event con un mensaje de error
                return redirect()->route('event.edit', $request->id)->withErrors(['error' => 'Ha ocurrido un error al crear el evento.']);
            }

        } catch (\Exception $e) {//este tipo de excepcion no deja crear los recursos si no esta autenticado el usuario que registra
            return redirect()->route('login')->withErrors(['error' => 'Ha ocurrido un error al crear el evento.']);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)/* ya */
    {
        $response = $this->validationView($id);
        if($response != null){
            return redirect()->back()->withErrors($response);
        }

        $places = Place::all();
        return view('event-map.edit', compact('id', 'places'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)/* falta poner todas las consideraciones */
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function validation(Request $request){//validacion de los datos que se envian por el formulario
        $eventMap = Validator::make($request->all(), [/* validacion de ids (Id de evento y de lugar)*/
            'id' => 'required|string|min:30|max:255',
            'option' => 'required|not_in:notaviable|string|min:3|max:255', // El valor no puede ser "notaviable"
        ]);

        if ($eventMap->fails()) {//retornamos los errores de los datos del evento
            return redirect()->back()->withErrors($eventMap)->withInput();
        }

        try {/* try catch de descencriptacion de id y de option*/
            $decryptedEventId = Crypt::decrypt($request->id);
            if($request->option != 'new'){//revisamos si el lugar es valido
                $place = Place::find(Crypt::decrypt($request->option));
            } 
        } catch (DecryptException $e) {//si hay error entonces retornamos a la vista con los errores y revisar en este punto cual es el valor de option y id
            return redirect()->back()->withErrors('ID inválido o corrupto.')->withInput();
        }
        
        if ($request->option == 'new') {/* validacion de los valores del mapa si option tiene el valor de new */
            $eventMap = Validator::make($request->all(), [
                'name' => 'required_if:option,new|string|max:255', // Requerido si option es "new"
                'address' => 'required_if:option,new|string|max:255', // Requerido si option es "new"
                'lat' => 'required_if:option,new|numeric|between:-90,90', // Requerido si option es "new"
                'lon' => 'required_if:option,new|numeric|between:-180,180', // Requerido si option es "new"
            ]);
            if ($eventMap->fails()) {//retornamos los errores de los datos del evento
                return redirect()->back()->withErrors($eventMap)->withInput();
            }
        }
    }

    public function validationView(string $id)
    {
        
        try {
            // Buscar el evento por ID
            $event = Event::find(Crypt::decrypt($id));
    
            if (!$event) {
                return 'El evento no existe.';
            }
            // Verificar si el evento está activo
            if ($event->status !== 'Activo') {//revisar este apartado porque puede ser contraproducente
                return 'El evento no está activo.';
            }
        
            // Verificar si la fecha del evento ya ha pasado
            if (Carbon::now()->gte(Carbon::parse($event->event_date))) {
                return 'La fecha del evento ya ha pasado.';
            }

        } catch (DecryptException $e) {
        // Manejar el error de desencriptación
            return redirect()->back()->withErrors('ID inválido o corrupto.');
        }
    }




}
