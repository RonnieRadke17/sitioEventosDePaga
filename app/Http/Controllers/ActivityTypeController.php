<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\EncryptService\EncryptService;
use App\Models\Activity;
use App\Models\type;

use App\Http\Requests\SportRequest\StoreSportRequest;
use App\Http\Requests\SportRequest\UpdateSportRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class ActivityTypeController extends Controller
{
    protected $encryptService;

    public function __construct(EncryptService $encryptService)
    {
        $this->encryptService = $encryptService;
    }

    /**
     * Display a listing of the resource.
     */
    /* falta encriptar el contenido de los types */
    public function form(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('activities.index')->withErrors('ID inválido.');
        }
        $activity = Activity::withTrashed()->find($decrypted_id);
        if (!$activity) {
            return redirect()->route('activities.index')->withErrors('Actividad no encontrada.');
        }

        $selectedTypes = null;
        // Verificamos si la actividad tiene tipos asociados
        $activity->load('types');

        //aqui verificamos si la actividad tiene tipos asociados

        $selectedTypes = !$activity->types->isEmpty() ? $activity->types->pluck('id')->toArray() : null;

        $types = Type::all(); //obtener todos los tipos disponibles

        return view('activity-types.form',compact('activity', 'types','selectedTypes','id'));
    }
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)/* falta implementar el request */
    {
        $decrypted_id = $this->encryptService->decrypt($request->input('activity_id'));

        if (!$decrypted_id) {
            return redirect()->route('activities.index')->withErrors('ID inválido.');
        }
        $activity = Activity::withTrashed()->find($decrypted_id);
        if (!$activity) {
            return redirect()->route('activities.index')->withErrors('Actividad no encontrada.');
        }

        // Asociar los tipos enviados desde el formulario
        $selectedTypes = $request->input('selectedTypes', []);

        // Si vienen IDs válidos, los asociamos
        if (!empty($selectedTypes)) {
            $activity->types()->attach($selectedTypes);
        }

    return redirect()->route('activities.index')->with('message', 'Tipos asociados correctamente.');

    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)/* falta el request */
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('activities.index')->withErrors('ID inválido.');
        }
        $activity = Activity::withTrashed()->find($decrypted_id);
        if (!$activity) {
            return redirect()->route('activities.index')->withErrors('Actividad no encontrada.');
        }
        // Actualizar los tipos asociados
        $selectedTypes = $request->input('selectedTypes', []);

        // Sincronizar la relación (actualiza eliminando los no seleccionados y agregando los nuevos)
        $activity->types()->sync($selectedTypes);
        
        return redirect()->route('activities.index')->with('message', 'Tipos asociados correctamente.');

    }

}
