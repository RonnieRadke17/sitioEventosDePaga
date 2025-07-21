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

    public function edit(string $id){
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('activities.index')->withErrors('ID inválido.');

        $activity = Activity::find($decrypted_id);
        if (!$activity) return redirect()->route('activities.index')->withErrors('Tipo no encontrado.');

        $sports = Sport::all();
        return view('activities.form', compact('activity', 'id','sports'));
    }

    public function update(UpdateActivityRequest $request, string $id){
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('activities.index')->withErrors('ID inválido.');

        $activity = Activity::find($decrypted_id);
        if (!$activity) return redirect()->route('activities.index')->withErrors('Tipo no encontrado.');

        $activity->update($request->validated());

        return redirect()->route('activities.index')->with('message', 'Actividad actualizada correctamente.');
    }
   
}
