<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Services\EncryptService\EncryptService;
use App\Models\Event;
use App\Models\Multimedia;
use Illuminate\Support\Facades\Storage;
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
    public function form(string $id)//ya está
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('events.index')->withErrors('ID inválido.');
        }
        $event = Event::withTrashed()->find($decrypted_id);
        if (!$event) {
            return redirect()->route('events.index')->withErrors('Evento no encontrado.');
        }

        // Verificamos si el evento tiene multimedia asociada
        $event->load('multimedias');

        /* condición que revisa si se modifica o se crea */
        $multimedia = $event->multimedias()->exists() ?: null;

        $haskit = $event->kit_delivery ? true : null;

        $cover = $event->multimedias()->where('type', 'cover')->first();
        $kit = $event->multimedias()->where('type', 'kit')->first();
        $content = $event->multimedias()->where('type', 'content')->get();


        return view('event-multimedia.form',compact('event','multimedia','id','cover','haskit','kit','content'));
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventMultimediaRequest $request)//ya está
    {
        $eventId = $request->input('event_id');

        // Cover
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');

            Multimedia::create([
                'url' => $coverPath,
                'event_id' => $eventId,
                'type' => 'cover',
            ]);
        }

        // Kit
        if ($request->hasFile('kit')) {
            $kitPath = $request->file('kit')->store('kits', 'public');

            Multimedia::create([
                'url' => $kitPath,
                'event_id' => $eventId,
                'type' => 'kit',
            ]);
        }

        // Content (pueden ser múltiples imágenes)
        if ($request->hasFile('content')) {
            foreach ($request->file('content') as $contentFile) {
                $contentPath = $contentFile->store('contents', 'public');

                Multimedia::create([
                    'url' => $contentPath,
                    'event_id' => $eventId,
                    'type' => 'content',
                ]);
            }
        }

        return redirect()->route('events.index')->with('success', 'Imágenes guardadas correctamente.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventMultimediaRequest $request, string $id)//ya está
    {
        if (empty($request->except('_token', '_method','event_id'))) {
            return redirect()->route('events.index')->withErrors('No se han realizado cambios.');
        }
        
        // Cover
        if ($request->hasFile('cover')) {
            $cover = Multimedia::where('event_id', $request->event_id)->where('type', 'cover')->first();

            // Eliminar archivo anterior si existe
            if ($cover && Storage::disk('public')->exists($cover->url)) {
                Storage::disk('public')->delete($cover->url);
            }

            // Guardar nuevo archivo
            $coverPath = $request->file('cover')->store("covers", 'public');

            // Actualizar registro existente o crearlo si no existía
            Multimedia::updateOrCreate(
                ['event_id' => $request->event_id, 'type' => 'cover'],
                ['url' => $coverPath]
            );
        }

        // Kit
        if ($request->hasFile('kit')) {
            $kit = Multimedia::where('event_id', $request->event_id)->where('type', 'kit')->first();

            if ($kit && Storage::disk('public')->exists($kit->url)) {
                Storage::disk('public')->delete($kit->url);
            }

            $kitPath = $request->file('kit')->store("kits", 'public');

            Multimedia::updateOrCreate(
                ['event_id' => $request->event_id, 'type' => 'kit'],
                ['url' => $kitPath]
            );
        }

        /* content */
        if ($request->hasFile('content')) {
            // Eliminar archivos y registros anteriores
            $contents = Multimedia::where('event_id', $request->event_id)->where('type', 'content')->get();

            foreach ($contents as $content) {
                if (Storage::disk('public')->exists($content->url)) {
                    Storage::disk('public')->delete($content->url);
                }
                $content->delete();
            }

            // Guardar nuevas imágenes
            foreach ($request->file('content') as $file) {
                $contentPath = $file->store("contents", 'public');

                Multimedia::create([
                    'event_id' => $request->event_id,
                    'type' => 'content',
                    'url' => $contentPath
                ]);
            }
        }

        return redirect()->route('events.index')->with('success', 'Imágenes actualizadas correctamente.');
    }

}
