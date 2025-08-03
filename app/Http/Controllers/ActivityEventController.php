<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

/* use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
 */

use App\Services\EncryptService\EncryptService;
use App\Models\Activity;
use App\Models\ActivityEvent;
use App\Models\Event;
use App\Models\Sub;
use Carbon\Carbon;
use App\Http\Requests\ActivityEventRequest\StoreActivityEventRequest;
use App\Http\Requests\ActivityEventRequest\UpdateActivityEventRequest;

class ActivityEventController extends Controller
{    

    protected $encryptService;

    public function __construct(EncryptService $encryptService)
    {
        $this->encryptService = $encryptService;
    }

    /**
     * Display a listing of the resource.
     */
    public function form(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('events.index')->withErrors('ID inválido.');
        }
        $event = Event::withTrashed()->find($decrypted_id);
        if (!$event) {
            return redirect()->route('events.index')->withErrors('Event no encontrado.');
        }

        $activities = Activity::all();
        $subs = Sub::all();
        /* relación con actividades del evento */
        $activitiesEvent = $event->activityEvents()->exists() ?: null;


        $selectedActivities = $event->activityEvents()->pluck('activity_id')->unique()->toArray();

        $selectedGenders = $event->activityEvents()
            ->get()
            ->groupBy('activity_id');

        return view('activity-events.form', compact('activities','subs','event','id','activitiesEvent','selectedActivities','selectedGenders'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityEventRequest $request)//ya
    {
        $decrypted_id = $this->encryptService->decrypt($request->input('event_id'));

        if (!$decrypted_id) {
            return redirect()->route('events.index')->withErrors('ID inválido.');
        }
        $event = Event::withTrashed()->find($decrypted_id);
        if (!$event) {
            return redirect()->route('events.index')->withErrors('Evento no encontrada.');
        }

        $genders = $request->input('genders', []);

        // Eliminar registros anteriores si aplica (opcional)
        ActivityEvent::where('event_id', $decrypted_id)->delete();

        foreach ($genders as $activityId => $genderGroups) {
            foreach ($genderGroups as $gender => $subs) {
                foreach ($subs as $subId => $value) {
                    ActivityEvent::create([
                        'event_id'     => $decrypted_id,
                        'activity_id'  => $activityId,
                        'sub_id'       => $subId,
                        'gender'       => $gender,
                    ]);
                }
            }
        }

        return redirect()->route('events.index')->with('success', 'Actividades registradas correctamente');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreActivityEventRequest $request, string $id)
    {
        $eventId = $request->getDecryptedEventId();
        $genders = $request->input('genders', []);
        $selectedActivities = $request->input('selected_activities', []);

        // 🗑️ 1. Borrar todos los registros actuales del evento
        ActivityEvent::where('event_id', $eventId)->delete();

        // ✅ 2. Insertar los nuevos (solo si hay seleccionados)
        foreach ($genders as $activityId => $genderGroups) {
            foreach ($genderGroups as $gender => $subs) {
                foreach ($subs as $subId => $value) {
                    ActivityEvent::create([
                        'event_id'    => $eventId,
                        'activity_id' => $activityId,
                        'sub_id'      => $subId,
                        'gender'      => $gender,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Actividades actualizadas correctamente.');
    }    
}
