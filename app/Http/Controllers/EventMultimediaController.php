<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Services\EncryptService\EncryptService;
use App\Models\Event;

use App\Http\Requests\EventMultimediaRequest\StoreEventMultimediaRequest;
use App\Http\Requests\EventMultimediaRequest\UpdateEventMultimediaRequest;




class EventMultimediaController extends Controller
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
            return redirect()->route('events.index')->withErrors('Evento no encontrado.');
        }

        $selectedMultimedia = null;
        // Verificamos si la actividad tiene tipos asociados
        $event->load('multimedias');


        //dd($event,$event->multimedias());

        //hasta aqui vamos bien

        //aqui verificamos si la actividad tiene tipos asociados

        $selectedMultimedia = !$event->multimedias->isEmpty() ? $event->multimedias->pluck('id')->toArray() : null;

        //$types = Type::all(); //obtener todos los tipos disponibles

        return view('event-multimedia.form',compact('event','selectedMultimedia','id'));
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

}
