<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\EncryptService\EncryptService;
use App\Models\Category;
use App\Models\Event;

use App\Http\Requests\SportRequest\StoreSportRequest;
use App\Http\Requests\SportRequest\UpdateSportRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CategoryEventController extends Controller
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
            return redirect()->route('events.index')->withErrors('ID inválido.');
        }
        $event = Event::withTrashed()->find($decrypted_id);
        if (!$event) {
            return redirect()->route('events.index')->withErrors('Actividad no encontrada.');
        }

        $selectedCategories = null;
        // Verificamos si la actividad tiene tipos asociados
        $event->load('types');

        //aqui verificamos si la actividad tiene tipos asociados

        $selectedCategories = !$event->categories->isEmpty() ? $event->categories->pluck('id')->toArray() : null;

        $types = Type::all(); //obtener todos los tipos disponibles

        return view('activity-types.form',compact('activity', 'types','selectedCategories','id'));
    }
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)/* falta implementar el request */
    {
        $decrypted_id = $this->encryptService->decrypt($request->input('activity_id'));

        if (!$decrypted_id) {
            return redirect()->route('events.index')->withErrors('ID inválido.');
        }
        $event = Event::withTrashed()->find($decrypted_id);
        if (!$event) {
            return redirect()->route('events.index')->withErrors('Actividad no encontrada.');
        }

        // Asociar los tipos enviados desde el formulario
        $selectedCategories = $request->input('selectedCategories', []);

        // Si vienen IDs válidos, los asociamos
        if (!empty($selectedCategories)) {
            $event->categories()->attach($selectedCategories);
        }

    return redirect()->route('events.index')->with('message', 'Tipos asociados correctamente.');

    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)/* falta el request */
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('events.index')->withErrors('ID inválido.');
        }
        $event = Event::withTrashed()->find($decrypted_id);
        if (!$event) {
            return redirect()->route('events.index')->withErrors('Actividad no encontrada.');
        }
        // Actualizar los tipos asociados
        $selectedCategories = $request->input('selectedCategories', []);

        // Sincronizar la relación (actualiza eliminando los no seleccionados y agregando los nuevos)
        $event->categories()->sync($selectedCategories);
        
        return redirect()->route('events.index')->with('message', 'Tipos asociados correctamente.');

    }
}
