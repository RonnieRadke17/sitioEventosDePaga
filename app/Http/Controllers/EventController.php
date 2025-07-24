<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

use App\Http\Requests\SportRequest\StoreEventRequest;
use App\Http\Requests\SportRequest\UpdateEventRequest;
use App\Services\EncryptService\EncryptService;

class EventController extends Controller
{
    protected $encryptService;

    public function __construct(EncryptService $encryptService)
    {
        $this->encryptService = $encryptService;
    }

    public function index()
    {
        /* falta poner el evento con la primera imagen relacionada de tipo cover */
        $events = Event::paginate(10);
        $events = $this->encryptService->encrypt($events);
        $type = 'active';

        return view('events.index', compact('events', 'type'));
    }

    public function create()
    {         
        $event = new Event();
        return view('events.form', compact('event'));
    }

    /* metodos para probar */

    public function store(StoreEventRequest $request){
        try {
            Event::create($request->validated());
            return redirect()->route('events.index')->with('message', 'Evento creado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('events.index')->withErrors('Error al crear el evento.');
        }
    }

    public function edit(string $id){
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('events.index')->withErrors('ID inválido.');

        $event = Event::find($decrypted_id);
        
        if (!$event) return redirect()->route('events.index')->withErrors('Evento no encontrada.');

        return view('events.form', compact('event', 'id'));
    }

    
    public function update(UpdateEventRequest $request, string $id){

        if (empty($request->except('_token', '_method'))) {
            return redirect()->route('events.index')->withErrors('No se han realizado cambios.');
        }

        // Verificar si el ID está encriptado y es válido
        $decrypted_id = $this->encryptService->decrypt($id);
      
        if ($decrypted_id === null) {
            return redirect()->route('events.index')->withErrors('Error al procesar la solicitud.');
        }

        $event = null;
        //si el id es valido, buscamos la Sub
        if($decrypted_id){
            $event = Event::find($decrypted_id);
            
            if (!$event) {
                return redirect()->route('events.index')->withErrors('Sub no encontrada.');   
            }
        }
        // Actualizar la Sub con los datos validados
        $event->fill($request->validated());
        $event->save();

        return redirect()->route('events.index')->with('message', 'Sub actualizada correctamente.');
    }

    /* mandar la información del evento junto con los demás elementos que tiene seleccionados */
    public function show($id)
    {
    }

    
    public function content($type)
    {
        
        try {
            // Elegir la consulta base según el filtro recibido
            if ($type === 'active') {
                $query = Event::query();
            }
            else if ($type === 'trashed') {
                $query = Event::onlyTrashed();
            } elseif ($type === 'all') {
                $query = Event::withTrashed();
            } else {
                // Si el tipo no es válido, redirigir o manejar el error
                return redirect()->route('events.index')->withErrors('Tipo de contenido no válido.');
            }

            // Obtener resultados paginados
            $events = $query->paginate(10);

            $events = $this->encryptService->Encrypt($events);

            // Pasar el tipo actual para marcar la selección en el frontend
            return view('events.index', compact('events', 'type'));

        } catch (\Throwable $th) {
            return back()->withErrors('Ocurrió un error al cargar los registros.');
        }
    }

    public function destroy(string $id){
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('events.index')->withErrors('ID inválido.');

        $event = Event::find($decrypted_id);
        if (!$event) return redirect()->route('events.index')->withErrors('Actividad no encontrada.');

        $event->delete();
        return redirect()->route('events.index')->with('message', 'Actividad eliminada correctamente.');
    }
   
     /**
     * Restore the specified soft-deleted resource.
     */
    public function restore(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('events.index')->withErrors('ID inválido.');
        }

        $event = Event::withTrashed()->find($decrypted_id);

        if (!$event) {
            return redirect()->route('events.index')->withErrors('actividad no encontrada.');
        }

        if (!$event->trashed()) {
            return redirect()->route('events.index')->withErrors('La actividad no está inhabilitada.');
        }

        $event->restore();

        return redirect()->route('events.index')->with('message', 'Actividad restaurada correctamente.');
    }

    /**
     * Permanently delete the specified resource.
     */
    public function forceDelete(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('events.index')->withErrors('ID inválido.');
        }

        $event = Event::withTrashed()->find($decrypted_id);

        if (!$event) {
            return redirect()->route('events.index')->withErrors('Actividad no encontrada.');
        }

        if (!$event->trashed()) {
            return redirect()->route('events.index')->withErrors('La actividad no está inhabilitada, no se puede eliminar permanentemente.');
        }

        try {
            $event->forceDelete();
            return redirect()->route('events.index')->with('message', 'Actividad eliminada permanentemente.');
        } catch (\Throwable $e) {
            return redirect()->route('events.index')->withErrors('Error al eliminar permanentemente: ');
        }
    }

}
