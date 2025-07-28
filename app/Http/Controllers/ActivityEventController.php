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
    /* falta encriptar el contenido de los types */
    public function form(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('events.index')->withErrors('ID inválido.');
        }
        $event = Event::withTrashed()->find($decrypted_id);
        if (!$event) {
            return redirect()->route('event.index')->withErrors('Actividad no encontrada.');
        }

        $selectedActivities = null;
        // Verificamos si el evento tiene actividades asociadas
        /* hasta aqui vamos bien ya lo demás es más delicado revisarlo */
        
        
        
        $event->load('activities');
        //aqui verificamos si la actividad tiene tipos asociados

        $selectedActivities = !$event->types->isEmpty() ? $event->types->pluck('id')->toArray() : null;

        $types = Type::all(); //obtener todos los tipos disponibles

        return view('activity-types.form',compact('activity', 'types','selectedActivities','id'));
    }






    
}
