<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Sport;
use App\Http\Requests\ActivityRequest\StoreActivityRequest;
use App\Http\Requests\ActivityRequest\UpdateActivityRequest;
use App\Services\EncryptService\EncryptService;



class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $encryptService;

    public function __construct(EncryptService $encryptService)
    {
        $this->encryptService = $encryptService;
    }

    public function index()
    {
        $activities = Activity::paginate(10);
        $activities = $this->encryptService->encrypt($activities);
        $type = 'active';

        return view('activities.index', compact('activities', 'type'));
    }

    public function create(){
        $activity = new Activity;
        $sports = Sport::all();
        //$sports = $this->encryptService->EncryptSelectors($sports);
        return view('activities.form',compact('activity','sports'));
    }

    public function store(StoreActivityRequest $request){
        try {
            Activity::create($request->validated());
            return redirect()->route('activities.index')->with('message', 'Tipo creado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('activities.index')->withErrors('Error al crear el tipo.');
        }
    }

    public function show(string $id){
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('activities.index')->withErrors('ID inválido.');

        /* buscar hasta en trasehd */
        /* $activity = Activity::find($decrypted_id); */
        $activity = Activity::withTrashed()->find($decrypted_id);
        if (!$activity) return redirect()->route('activities.index')->withErrors('Tipo no encontrado.');

        $sports = Sport::all();
        return view('activities.show', compact('activity', 'sports'));
    }

    public function edit(string $id){
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('activities.index')->withErrors('ID inválido.');

        $activity = Activity::find($decrypted_id);
        

        if (!$activity) return redirect()->route('activities.index')->withErrors('Actividad no encontrada.');

        $sports = Sport::all();
        return view('activities.form', compact('activity', 'id','sports'));
    }

    public function update(UpdateActivityRequest $request, string $id){

        if (empty($request->except('_token', '_method'))) {
            return redirect()->route('activities.index')->withErrors('No se han realizado cambios.');
        }

        // Verificar si el ID está encriptado y es válido
        $decrypted_id = $this->encryptService->decrypt($id);
      
        if ($decrypted_id === null) {
            return redirect()->route('activities.index')->withErrors('Error al procesar la solicitud.');
        }

        $activity = null;
        //si el id es valido, buscamos la actividad
        if($decrypted_id){
            $activity = Activity::find($decrypted_id);
            
            if (!$activity) {
                return redirect()->route('activities.index')->withErrors('Actividad no encontrada.');   
            }
        }
        // Actualizar la actividad con los datos validados
        $activity->fill($request->validated());
        $activity->save();

        return redirect()->route('activities.index')->with('message', 'Actividad actualizada correctamente.');
    }

    public function content($type)
    {
        
        try {
            // Elegir la consulta base según el filtro recibido
            if ($type === 'active') {
                $query = Activity::query();
            }
            else if ($type === 'trashed') {
                $query = Activity::onlyTrashed();
            } elseif ($type === 'all') {
                $query = Activity::withTrashed();
            } else {
                // Si el tipo no es válido, redirigir o manejar el error
                return redirect()->route('sports.index')->withErrors('Tipo de contenido no válido.');
            }

            // Obtener resultados paginados
            $activities = $query->paginate(10);

            $activities = $this->encryptService->Encrypt($activities);

            // Pasar el tipo actual para marcar la selección en el frontend
            return view('activities.index', compact('activities', 'type'));

        } catch (\Throwable $th) {
            return back()->withErrors('Ocurrió un error al cargar los registros.');
        }
    }

    public function destroy(string $id){
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('activities.index')->withErrors('ID inválido.');

        $activity = Activity::find($decrypted_id);
        if (!$activity) return redirect()->route('activities.index')->withErrors('Actividad no encontrada.');

        $activity->delete();
        return redirect()->route('activities.index')->with('message', 'Actividad eliminada correctamente.');
    }
   
     /**
     * Restore the specified soft-deleted resource.
     */
    public function restore(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('activities.index')->withErrors('ID inválido.');
        }

        $activity = Activity::withTrashed()->find($decrypted_id);

        if (!$activity) {
            return redirect()->route('activities.index')->withErrors('actividad no encontrada.');
        }

        if (!$activity->trashed()) {
            return redirect()->route('activities.index')->withErrors('La actividad no está inhabilitada.');
        }

        $activity->restore();

        return redirect()->route('activities.index')->with('message', 'Actividad restaurada correctamente.');
    }

    /**
     * Permanently delete the specified resource.
     */
    public function forceDelete(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('activities.index')->withErrors('ID inválido.');
        }

        $activity = Activity::withTrashed()->find($decrypted_id);

        if (!$activity) {
            return redirect()->route('activities.index')->withErrors('Actividad no encontrada.');
        }

        if (!$activity->trashed()) {
            return redirect()->route('activities.index')->withErrors('La actividad no está inhabilitada, no se puede eliminar permanentemente.');
        }

        try {
            $activity->forceDelete();
            return redirect()->route('activities.index')->with('message', 'Actividad eliminada permanentemente.');
        } catch (\Throwable $e) {
            return redirect()->route('activities.index')->withErrors('Error al eliminar permanentemente: ');
        }
    }


}
